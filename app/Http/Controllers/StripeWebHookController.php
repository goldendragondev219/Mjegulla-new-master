<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\OwnerCjDropshippingLowBalance;
use App\Mail\OrderComplete;
use App\Mail\DeclineTransactionMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Stripe\Webhook;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Price;
use Stripe\Plan;
use App\Models\PaymentProviders;
use App\Models\SubscriptionPackages;
use App\Models\CustomerBilling;
use App\Models\CjDropshipping;
use App\Models\ShippingRates as Countries;
use App\Models\Shop;
use App\Models\Orders;
use App\Models\User;
use App\Models\Customer as Buyer;
use App\Models\Referral;

class StripeWebHookController extends Controller
{


    public function handleStripeWebhook(Request $request)
    {
        Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        $event = null;
    
        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, PaymentProviders::select('wh_secret')->where('name', 'Stripe')->value('wh_secret')
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }
        switch ($event->type) {
            case 'customer.subscription.updated':
                if (isset($event->data->previous_attributes) && !isset($event->data->object->canceled_at)) {
                    $this->handleSubscriptionUpdated($event->data->object);
                }
                if(isset($event->data->object->canceled_at)){
                    $this->handleSubscriptionCanceled($event->data->object);
                }
                break;

            case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'checkout.session.completed':
                //Only for shop payments, no subscriptions.
                if($event->data->object->metadata->shop_id && !$event->data->object->metadata->type){
                    $this->handleCheckoutCompleted($event->data->object);
                }
                break;

            case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($event->data->object);
                break;

            default:
                return response('Unhandled webhook event', 200);
        }
        
    
        return response('Webhook received', 200);
    }
    


    private function handleSubscriptionUpdated($subscription)
    {
        $subscriptionId = $subscription->id;
        $metadata = $subscription->metadata;
    
        try {
            // Check if the subscription exists in the database
            $subscriptionPackage = SubscriptionPackages::where('sub_id', $subscriptionId)->firstOrFail();
        

            //check if shop owner is a referred user, then add 5EUR to referrer referral_balance
            if($subscriptionPackage->ends_at < Carbon::createFromTimestamp($subscription->current_period_end)){
                $shop_owner_id = Shop::where('shop_id', $subscriptionPackage->shop_id)->value('user_id');
                $refer_id = Referral::where('refered_id', $shop_owner_id)->value('refer_id');
                if($refer_id){
                    $refer_user = User::where('id', $refer_id)->increment('referral_balance', 5);
                }                
            }


            // Update shop owner ends_at data with the subscription end period
            SubscriptionPackages::where('sub_id', $subscriptionId)->update([
                'ends_at' => Carbon::createFromTimestamp($subscription->current_period_end),
            ]);


            Log::info('Subscription ' . $subscriptionId . ' updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the subscription ID does not exist in the database
            Log::error('Subscription ' . $subscriptionId . ' does not exist in the database');
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('An error occurred while updating subscription: ' . $e->getMessage());
        }        
    }
    
    
    private function handleSubscriptionCanceled($subscription)
    {
        $subscriptionId = $subscription->id;
        $metadata = $subscription->metadata;
    
    
        try {
            // Check if the subscription exists in the database
            $subscriptionPackage = SubscriptionPackages::where('sub_id', $subscriptionId)->firstOrFail();
        
            // Update shop owner ends_at data with the subscription end period
            if($subscription->cancel_at_period_end){
                //will end at current period
                SubscriptionPackages::where('sub_id', $subscriptionId)->update([
                    'ends_at' => Carbon::createFromTimestamp($subscription->current_period_end),
                    'will_cancel' => 'yes',
                ]);
            }else{
                SubscriptionPackages::where('sub_id', $subscriptionId)->update([
                    'ends_at' => Carbon::createFromTimestamp($subscription->current_period_end),
                ]);
            }



            Log::info('Subscription ' . $subscriptionId . ' updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the subscription ID does not exist in the database
            Log::error('Subscription ' . $subscriptionId . ' does not exist in the database');
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('An error occurred while updating subscription: ' . $e->getMessage());
        }        
    }


    
    private function handleSubscriptionDeleted($subscription)
    {
        $subscriptionId = $subscription->id;
        $metadata = $subscription->metadata;
    
        try {
            // Check if the subscription exists in the database
            $subscriptionPackage = SubscriptionPackages::where('sub_id', $subscriptionId)->firstOrFail();
        

            //Make available products 0
            if($subscriptionPackage){
                Shop::where('id', $subscriptionPackage->shop_id)->update([
                    'products_available' => 0,
                    'active' => 'no',
                ]);
            }


            // Delete subscription package
            SubscriptionPackages::where('sub_id', $subscriptionId)->delete();



            Log::info('Subscription ' . $subscriptionId . ' deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the subscription ID does not exist in the database
            Log::error('Subscription ' . $subscriptionId . ' does not exist in the database');
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('An error occurred while updating subscription: ' . $e->getMessage());
        }        
    }


    public function handlePaymentFailed($failed_payment)
    {
        $user_email = $failed_payment->last_payment_error->payment_method->billing_details->email;
        $user_name = $failed_payment->last_payment_error->payment_method->billing_details->name;
        $decline_message = $failed_payment->last_payment_error->message;
        Log::info('DECLINE EMAIL --  '.$user_email.' DECLINE MESSAGE - '.$decline_message);
        $lang = User::where('email', $user_email)->value('default_language');
        $data = [
            'name' => $user_name,
            'message' => $decline_message,
            'lang' => $lang ?? 'sq',
        ];
        Mail::to($user_email)->send(new DeclineTransactionMail($data));
    }



    public function handleCheckoutCompleted($checkout){
        $metadata = $checkout->metadata;
        Log::info('Checkout metadata: ' . json_encode($metadata));

        //send data to CjDropshipping
        $shop = Shop::find($metadata->shop_id);
        $cj_data = CjDropshipping::where('shop_id', $metadata->shop_id)
        ->where('user_id', $shop->user_id)->first();

        $user = User::find($shop->user_id);
        $customer = Buyer::find($metadata->user_id);

        $billing_data = CustomerBilling::where('user_id', $metadata->user_id)->first();
        $country = Countries::find($billing_data->country);

        //product (variants)
        $products = [];
        $variants = json_decode($metadata->variant_id);
        if (is_array($variants)) {
            foreach ($variants as $variant) {
                $products[] = [
                    'quantity' => 1,
                    'vid' => $variant,
                    'shippingName' => $billing_data->first_name . ' ' . $billing_data->last_name,
                ];
            }
        } else {
            Log::info('This is not an array lol: ' . json_encode($metadata));
        }
        
        $order_data = [
            'orderNumber' => $metadata->order_id,
            'shippingZip' => $billing_data->zip,
            'shippingCountryCode' => strtoupper($country->country_code),
            'shippingCountry' => $country->country,
            'shippingProvince' => $billing_data->city,
            'shippingCity' => $billing_data->city,
            'shippingAddress' => $billing_data->address,
            'shippingCustomerName' => $billing_data->first_name.' '.$billing_data->last_name,
            'shippingPhone' => $billing_data->phone,
            'remark' => $billing_data->order_note,
            'fromCountryCode' => 'CN',
            'logisticName' => $metadata->cj_postal,
            'products' => $products,
        ];
        //import order to Cj Dropshipping
        $import_order = (new CjDropshippingController)->cj_create_order($cj_data->generated_token, $order_data);
        $fulfill_order = (new CjDropshippingController)->cj_confirm_order($cj_data->generated_token, $metadata->order_id);

        
        $order = Orders::find($metadata->order_id);
        if(!$order){
            Log::error('Order deleted.... webhook die');
            die();
        }
        $cj_balance = (new CjDropshippingController)->cj_balance($cj_data->generated_token);

        //check if shop owner has balance to pay the order in their CjDropshipping account
        $mail_data = [
            'shop' => $shop,
            'user' => $user,
            'order_amount' => $order->amount,
        ];
        if($order->amount <= $cj_balance){
            $pay_balance = (new CjDropshippingController)->cj_pay_balance($metadata->order_id, $cj_data->generated_token);
            Log::info('PAY BALANCE - '. json_encode($pay_balance));
            if($pay_balance['result']){
                $order->cj_paid = 'yes';
                $order->save();
                Log::info('PAY BALANCE - YES');

            }else{
                $order->cj_paid = 'no';
                $order->save();
                Log::info('PAY BALANCE - NO');

                //send email to shop owner and tell that has 24 hours to make the payment or order will be refunded

                Mail::to($user->email)->send(new OwnerCjDropshippingLowBalance($mail_data));

            }
        }else{
            //send email to shop owner and tell that has 24 hours to make the payment or order will be refunded
            Mail::to($user->email)->send(new OwnerCjDropshippingLowBalance($mail_data));
        }

        //mail owner about the new sale
        Mail::to($user->email)->send(new OrderComplete($mail_data, 'owner'));


        //mail customer about the order
        $mail_data = [
            'shop' => $shop,
            'user' => $customer,
            'order_amount' => $order->amount,
        ];
        Mail::to($customer->email)->send(new OrderComplete($mail_data, 'customer'));

    }
    
}
