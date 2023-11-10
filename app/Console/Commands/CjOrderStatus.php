<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


use App\Mail\OrderStatusUpdateCustomer;
use Illuminate\Support\Facades\Mail;


use App\Http\Controllers\CjDropshippingController;
use App\Models\CjDropshipping;
use App\Models\User;
use App\Models\Shop;
use App\Models\Orders;
use App\Models\Customer;

class CjOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cj:checkOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cj dropshipping check orders and update status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Orders::where('cj_status', '!=', 'DELIVERED')
        ->where('finished', 'yes')
        ->where('payment_method', 'credit_card')
        ->get();
    
    foreach ($orders as $order) {
        sleep(1); // CJ Dropshipping allows 1 request/1second.
        $shop_id = $order->shop_id;
        $shop = Shop::where('id', $shop_id)->first();
        $shop_owner_id = $shop->user_id;
        $customer = Customer::where('id', $order->user_id)->select('email', 'name')->first();
        $cj_token = CjDropshipping::where('user_id', $shop_owner_id)->value('generated_token');
        
    
        try {
            // Query order via Cj Dropshipping API
            $query_order = (new CjDropshippingController)->query_order($order->id, $cj_token);
            
            if(!$query_order['result']){
                Log::error('SHOP OWNER ID: '.$shop_owner_id.' SHOP ID: '.$shop_id.' ORDER ID: '.$order->id.' - CJ MESSAGE: '.$query_order['message']);
                continue;
            }

            $order_status_change = false;
            $tracking_number_change = false;
            // Check if the API response is valid before updating the database
            if (isset($query_order['data']['trackNumber'])) {
                $tracking = $query_order['data']['trackNumber'];
                if ($tracking !== $order->cj_tracking) {
                    $order->cj_tracking = $tracking;
                    $tracking_number_change = true;
                }
            }

            if (isset($query_order['data']['logisticName'])) {
                $shipping_company = $query_order['data']['logisticName'];
                if ($shipping_company !== $order->cj_postal) {
                    $order->cj_postal = $shipping_company;
                }
            }
    
            if (isset($query_order['data']['orderStatus'])) {
                $order_status = $query_order['data']['orderStatus'];
                if ($order_status !== $order->cj_status) {
                    $order->cj_status = $order_status;
                    $order_status_change = true;
                }

                //if status changed to UNSHIPPED means, the order has been paid.
                if($order_status == 'UNSHIPPED'){
                    $order->cj_paid = 'yes';
                }

            }
    
            // CANCELLED status should be treated.

            $order->save();
    
            //send email to customer that order has been updated
            if($order_status_change && $order->cj_status !== 'UNSHIPPED'){
                $mail_data = [
                    'shop_data' => $shop,
                    'order_id' => $order->id,
                    'order_status' => $order_status,
                    'customer_name' => $customer->name,
                    'tracking_number' => ($tracking_number_change) ? $order->cj_tracking : false,
                ];
                Mail::to($customer->email)->queue(new OrderStatusUpdateCustomer($mail_data));
            }


        } catch (\Exception $e) {
            Log::error('Error processing order ' . $order->id . ': ' . $e->getMessage());
            Log::info('DATA JSON -- '.json_encode($query_order));
        }
    }
    
    }
}
