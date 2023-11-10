<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerBilling;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Uuid;
use App\Models\Orders;
use App\Models\PaymentProviders;

class CustomerBillingController extends Controller
{

    //do not pass data without sanitizing.
    public function update($billing_data)
    {
        $billing = CustomerBilling::updateOrCreate(
            ['user_id' => $billing_data['user_id'], 'shop_id' => $billing_data['shop_id']],
            $billing_data
        );
        
        return $billing;
    }
    


    public function payViaStripe($amount,$shipping_price,$description,$shop_id,$billing_id,$product_id_json,$variant_id_json,$cj_postal)
    {
        Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));
        $user_id = auth()->guard('customers')->user()->id;
        $stripe_customer_id = auth()->guard('customers')->user()->customer_id;
        $order = new Orders();
        $order->user_id = $user_id;
        $order->shop_id = $shop_id;
        $order->billing_id = $billing_id;
        $order->product_id = $product_id_json;
        $order->variant_id = $variant_id_json;
        $order->shipping_price = number_format($shipping_price,2);
        $order->amount = number_format($amount,2);
        $order->cj_postal = $cj_postal;

        $order->save();
        

        $stripe_product = \Stripe\Product::create([
            'name' => 'ORDER - '.$order->id,
            'description' => $description,
        ]);
        
        $price = \Stripe\Price::create([
            'unit_amount' => (int) ($amount * 100), // Convert euros to cents
            'currency' => 'eur',
            'product' => $stripe_product->id,
        ]);
        
        $session = Session::create([
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'customer' => $stripe_customer_id,
            'mode' => 'payment',
            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $user_id,
                'shop_id' => $shop_id,
                'billing_id' => $billing_id,
                'product_id' => $product_id_json,
                'variant_id' => $variant_id_json,
                'cj_postal' => $cj_postal
            ],
            'automatic_tax' => ['enabled' => true],
            'customer_update' => ['address' => 'auto'],
            'success_url' => url('/order/completed') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/my/checkout'),
        ]);
        
        $order->stripe_payment_id = $price->id;
        $order->save();
        header('Location: ' . $session->url);
        exit;

    }


    public function payViaCash($amount,$shipping_price,$description,$shop_id,$billing_id,$product_id_json,$variant_id_json)
    {
        $user_id = auth()->guard('customers')->user()->id;
        $stripe_customer_id = auth()->guard('customers')->user()->customer_id;
        $order = new Orders();
        $order->user_id = $user_id;
        $order->shop_id = $shop_id;
        $order->billing_id = $billing_id;
        $order->product_id = $product_id_json;
        $order->variant_id = $variant_id_json;
        $order->shipping_price = number_format($shipping_price,2);
        $order->amount = number_format($amount,2);
        $order->completed = 'no'; // it should be completed after 24hours or 48 hours.
        $order->payment_method = 'cash';
        $order->stripe_payment_id = Str::uuid();
        $order->save();
        header('Location: ' . '/order/completed?session_id='.$order->stripe_payment_id);
        exit;

    }


}
