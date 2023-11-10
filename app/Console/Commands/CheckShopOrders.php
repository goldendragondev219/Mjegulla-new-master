<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShopDeactivationNotification;

use Carbon\Carbon;

use App\Models\Shop;
use App\Models\SubscriptionPackages;
use App\Models\Orders;
use App\Models\User;

class CheckShopOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:shopOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check every shop orders, and update the status of shop active (yes/no)';

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
        $shops = Shop::where('active', 'yes')
        ->get();
        $updated_shops = [];

        foreach ($shops as $shop) {    

            //check if shop has package, and which package
            $sub_package = SubscriptionPackages::where('shop_id', $shop->id)->first();

            //count orders this month for current shop
            $orders_this_month = Orders::where('shop_id', $shop->id)
            ->where('finished', 'yes')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

            //set plan as free for current shop.
            $shop_plan = 'Free';

            //if any other than free plan exists update the shop_plan.
            if($sub_package){
                $shop_plan = $sub_package->package_type;
            }

            if($shop_plan == 'Free'){
                if($orders_this_month >= 2){
                    $shop->active = 'no';
                    $updated_shops[] = [
                        'shop' => $shop,
                    ];
                }
            }

            if($shop_plan == 'Basic'){
                if($orders_this_month >= 1000){
                    $shop->active = 'no';
                    $updated_shops[] = [
                        'shop' => $shop,
                    ];
                }
            }

            if($shop_plan == 'Premium'){
                if($orders_this_month >= 5000){
                    $shop->active = 'no';
                    $updated_shops[] = [
                        'shop' => $shop,
                    ];
                }
            }

            $shop->save();

        }


        // Check if any shops were updated to 'no' and send an email.
        if (!empty($updated_shops)) {
            foreach ($updated_shops as $updatedShop) {
                $user_data = User::where('id', $updatedShop['shop']->user_id)->first();
                $data = [
                    'name' => $user_data['name'],
                    'lang' => $user_data['default_language'],
                    'store_name' => $updatedShop['shop']->shop_name,
                ];
                Mail::to($user_data['email'])->queue(new ShopDeactivationNotification($data));
            }
        }
    }
}
