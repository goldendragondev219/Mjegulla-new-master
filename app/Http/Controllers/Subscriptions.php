<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Price;
use Stripe\Plan;
use Stripe\Checkout\Session;
use App\Models\PaymentProviders;
use App\Models\User;
use App\Models\Referral;
use App\Models\SubscriptionPackages;
use App\Models\Shop;
use Auth;


class Subscriptions extends Controller
{
    public function cancel($plan_id)
    {
        
        if(Auth::check()){
            $customer_id = auth()->user()->customer_id;
            // Set your API key
            Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));

            // Retrieve the subscription object using the customer ID and plan ID
            $subscriptions = Subscription::all(['customer' => $customer_id, 'plan' => $plan_id, 'status' => 'active']);
            $subscription = $subscriptions->data[0];

            
            // Check if the authenticated user is the owner of the subscription
            if ($subscription->customer !== $customer_id) {
                return redirect()->back()->withErrors(trans('general.no_permission'));
            }

            // Check if the subscription is currently in the trial period
            if ($subscription->status === 'trialing') {
                // If the subscription is in the trial period, cancel it immediately
                $canceled = $subscription->cancel();
                return redirect()->back()->with('success', trans('general.subscription_cancelled_successfully'));
            } else {
                // If the subscription is not in the trial period, check if the current time is before the period end
                if (time() < $subscription->current_period_end) {
                    // If the current time is before the period end, set the cancel_at property to the end of the current billing period
                    $subscription->cancel_at_period_end = true;
                    $subscription->save();
                    SubscriptionPackages::where('sub_id', $subscription->id)->update(['will_cancel' => 'yes']);
                    return redirect()->back()->with('success', trans('general.subscription_cancelled_successfully'));
                } else {
                    // If the current time is after the period end, cancel the subscription immediately
                    $canceled = $subscription->cancel();
                    SubscriptionPackages::where('sub_id', $subscription->id)->update(['will_cancel' => 'yes']);
                    return redirect()->back()->with('success', trans('general.subscription_cancelled_successfully'));
                }
            }
        }
    }


    public function resubscribe($subscription_id)
    {
        if (Auth::check()) {
            $customer_id = auth()->user()->customer_id;
            // Set your API key
            Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));
    
            // Retrieve the subscription object using the subscription ID
            $subscription = \Stripe\Subscription::retrieve($subscription_id);


            // Check if the subscription is canceled or expired
            if ($subscription->status === 'canceled' || $subscription->status === 'incomplete_expired') {
                return redirect()->back()->withErrors(trans('general.can_not_resub_to_cancelled_or_expired_subscription'));
            }
    
            // Check if the authenticated user is the owner of the subscription
            if ($subscription->customer !== $customer_id) {
                return redirect()->back()->withErrors(trans('general.no_permission'));
            }
    
            // Reactivate the subscription
            $subscription->cancel_at = null;
            $subscription->save();
            SubscriptionPackages::where('sub_id', $subscription->id)->update(['will_cancel' => 'no']);

            if ($subscription->status === 'active') {
                return redirect()->back()->with('success', trans('general.sub_re_activated_successfully'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }
        }
    }
    

    public function createSubscription($request,$filename)
    {
       
        if ($request->subscription !== 'basic' && $request->subscription !== 'premium') {
            abort(401, 'Unauthorized');
        }
        
        
        Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));
        //create subscription plan.
        $plan = Plan::create([
            'amount' => ($request->subscription == 'basic' ? 1999 : 2999),
            'currency' => 'eur',
            'interval' => 'month',
            'product' => [
                'name' => ucfirst($request->subscription).' Plan ('.$request->shop_name.')',
                'metadata' => [
                    'user_id' => auth()->user()->id,
                    'shop_name' => $request->shop_name,
                    'my_shop_url' => $request->my_shop_url,
                    'shop_description' => $request->shop_description,
                    'shop_seo_keywords' => $request->shop_seo_keywords,
                    'shop_logo_url' => $filename,
                    'products_available' => ($request->subscription == 'basic' ? 50 : 'unlimited'),
                    'full_name' => $request->full_name,
                    'company' => $request->company,
                    'city' => $request->city,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'store_type' => $request->store_type,
                ],
            ],
        ]);
        
        //create session for subscription plan.
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'customer' => auth()->user()->customer_id,
                'subscription_data' => [
                    'items' => [[
                        'plan' => $plan->id,
                    ]],
                ],
                'metadata' => [
                    'user_id' => auth()->user()->id,
                    'shop_name' => $request->shop_name,
                    'my_shop_url' => $request->my_shop_url,
                    'shop_description' => $request->shop_description,
                    'shop_seo_keywords' => $request->shop_seo_keywords,
                    'shop_logo_url' => $filename,
                    'package' => ucfirst($request->subscription),
                    'full_details' => ucfirst($request->subscription).' Plan ('.$request->shop_name.')',
                    'products_available' => ($request->subscription == 'basic' ? 50 : 'unlimited'),
                    'full_name' => $request->full_name,
                    'company' => $request->company,
                    'city' => $request->city,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'store_type' => $request->store_type,

                ],
                'automatic_tax' => ['enabled' => true],
                'customer_update' => ['address' => 'auto'],
                'allow_promotion_codes' => true,
                'success_url' => route('home') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('home'),
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe error: '.$e->getMessage());
            return redirect('/home')->with('error', trans('general.error_proccessing_payment'));
        }
            
        header('Location: ' . $session->url);
        exit;
        
                
    }


    
    function calculateProratedAmount($old_price, $new_price, $subscription_ends) {
        $time_now = time();
        $days_in_month = date('t', $time_now);
        $days_remaining = (strtotime($subscription_ends) - $time_now) / 86400;
        $daily_rate = ($old_price / $days_in_month);
    
        if ($time_now >= strtotime($subscription_ends)) {
            return $new_price;
        }

        $prorated_amount = round(($daily_rate * $days_remaining) + ($new_price - $old_price), 2);
        return $prorated_amount;
    }
    
    


    function payProratedAmountOfUpgrade($amount, $name, $description, $metadata) {
        $amount = $amount * 100;
        // Set your Stripe API key
        Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));


            $plan = Plan::create([
                'amount' => $amount,
                'currency' => 'eur',
                'interval' => 'month',
                'product' => [
                    'name' => $name,
                    'metadata' => $metadata,
                ],
            ]);

            try {
                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'customer' => auth()->user()->customer_id,
                    'subscription_data' => [
                        'items' => [[
                            'plan' => $plan->id,
                        ]],
                    ],
                    'automatic_tax' => ['enabled' => true],
                    'customer_update' => ['address' => 'auto'],
                    'metadata' => $metadata,
                    'allow_promotion_codes' => true,
                    'success_url' => route('shop_package_upgrade') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('shop_package_upgrade') . '?session_id={CHECKOUT_SESSION_ID}',
                ]);

            } catch (\Stripe\Exception\ApiErrorException $e) {
                \Log::error('Stripe error: '.$e->getMessage());
                return url()->previous()->with('error', trans('general.error_proccessing_payment'));
            }
                
            header('Location: ' . $session->url);
            exit;
    }
    
    
    
    


    //upgrade package
    public function upgrade($shop_id,$package)
    {
        $shop = Shop::findOrFail($shop_id);
        if($shop->user_id === auth()->user()->id){
            if($package === 'Basic'){
                //we have to make subscription here not charge!!!!
                $plan_price = 19.99;
                $plan_name = 'Basic Plan Upgrade ('.$shop->shop_name.')';
                $metadata = [
                    'type' => 'New Plan',
                    'shop_id' => $shop->id,
                    'up_from' => 'Free',
                    'up_to' => 'Basic',
                    'user_id' => auth()->user()->id,
                    'customer_id' => auth()->user()->customer_id,
                ];
                $this->payProratedAmountOfUpgrade($plan_price, $plan_name, $plan_name, $metadata, true);
            }else{
                $plan_price = 29.99;
                $plan_name = 'Premium Plan Upgrade ('.$shop->shop_name.')';
                $active_package = SubscriptionPackages::where('shop_id', $shop->id)->first();
                if($active_package){
                    //$prorated_amount = $this->calculateProratedAmount($active_package->amount, $plan_price, $active_package->ends_at);
                    $metadata = [
                        'type' => 'Upgrade',
                        'shop_id' => $shop->id,
                        'up_from' => $active_package->package_type,
                        'up_to' => 'Premium',
                        'user_id' => auth()->user()->id,
                        'customer_id' => auth()->user()->customer_id,
                    ];
                    $this->payProratedAmountOfUpgrade($plan_price, $plan_name, $plan_name, $metadata);
                }else{
                    //we have to make subscription here not charge!!!!
                    $plan_price = 29.99;
                    $metadata = [
                        'type' => 'New Plan',
                        'shop_id' => $shop->id,
                        'up_from' => 'Free',
                        'up_to' => 'Premium',
                        'user_id' => auth()->user()->id,
                        'customer_id' => auth()->user()->customer_id,
                    ];
                    $this->payProratedAmountOfUpgrade($plan_price, $plan_name, $plan_name, $metadata);
                }
                
            }
        }else{
            abort(401, 'Unauthorized');
        }
    }




    //upgrade handle from stripe
    public function upgradeHandle($session_id)
    {
        Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));
        $session = Session::retrieve($session_id);
        


        if ($session->payment_status === 'paid') {
            $metadata = $session->metadata;


            //Don't allow user to create subscription package when expired.
            if(Carbon::createFromTimestamp($session->expires_at) < now()){
                return redirect()->back();
            }else{
                //make another check if subscription exist on DB
                $sub = SubscriptionPackages::where('sub_id', $session->subscription)->count();
                if($sub){
                    return redirect()->back();
                }
            }


            $shop = Shop::findOrFail($metadata['shop_id']);

            //create and change the current plan. if plan exist if not create new on DB
            if($metadata['type'] === 'Upgrade'){
                //here is upgrading the plan

                //get current subscription id
                $active_package = SubscriptionPackages::where('shop_id', $metadata['shop_id'])->first();

                if(isset($active_package->package_type)){
                    if($metadata['up_to'] === $active_package->package_type){
                        return redirect()
                                ->route('shop_package_upgrade', $shop->id)
                                ->with('success', trans('general.successfully_upgraded_shop'));
                    }                    
                }


                if($active_package){
                    //cancel the existing plan 
                    $subscription = \Stripe\Subscription::retrieve($active_package->sub_id);
                    $subscription->cancel();
                    
                    if($metadata['up_to'] === 'Premium'){
                        $name = 'Premium Plan ('.$shop->shop_name.')';
                        $package_type = 'Premium';
                        $total_products = 'unlimited';
                        $amount = 29.99;
                    }else{
                        $name = 'Basic Plan ('.$shop->shop_name.')';
                        $package_type = 'Basic';
                        $total_products = 50;
                        $amount = 19.99;
                    }
                    $metadata = [
                        'user_id' => auth()->user()->id,
                        'shop_id' => $shop->id,
                        'products_available' => $total_products,
                    ];
                    $subscription_id = $session->subscription;
                    $new_subscription = \Stripe\Subscription::retrieve($subscription_id);
                    $subscription_end = Carbon::createFromTimestamp($new_subscription->current_period_end);

                    //save the subscription to database, add one month to ends_at.
                    if($subscription_id){
                        $active_package->sub_id = $subscription_id;
                        $active_package->amount = $amount;
                        $active_package->package_type = $package_type;
                        $active_package->details = $name;
                        $active_package->ends_at = $subscription_end;
                        $active_package->save();

                        //update shop available products
                        if($package_type === 'Basic'){
                            $shop_total_products = $shop->total_products;
                            $products_to_add = $total_products - $shop_total_products;
                            $shop->products_available = $products_to_add;
                            $shop->stripe_session = $session_id;
                            $shop->active = 'yes';
                            $shop->save();
                        }else{
                            $shop->stripe_session = $session_id;
                            $shop->products_available = 'unlimited';
                            $shop->active = 'yes';
                            $shop->save();
                        }
 
                        //increment referral funds, if referer exists
                        $refer_id = Referral::where('refered_id', $shop->user_id)->value('refer_id');
                        if($refer_id){
                            $refer_user = User::where('id', $refer_id)->increment('referral_balance', 5);
                        } 

                        return redirect()
                            ->route('shop_package_upgrade', $shop->id)
                            ->with('success', trans('general.successfully_upgraded_shop'));
                    }

                }else{
                    return redirect()->back()->with('error', 'ERROR! '.trans('general.order_error_desc'));
                }

            }else{
                //new subscription upgrade, from free shop.
                if($metadata['up_to'] === 'Premium'){
                    $name = 'Premium Plan ('.$shop->shop_name.')';
                    $package_type = 'Premium';
                    $total_products = 'unlimited';
                    $amount = 29.99;
                }else{
                    $name = 'Basic Plan ('.$shop->shop_name.')';
                    $package_type = 'Basic';
                    $total_products = 50;
                    $amount = 19.99;
                }
                $metadata = [
                    'user_id' => auth()->user()->id,
                    'shop_id' => $shop->id,
                    'products_available' => $total_products,
                ];
                $subscription_id = $session->subscription;
                $new_subscription = \Stripe\Subscription::retrieve($subscription_id);
                $subscription_end = Carbon::createFromTimestamp($new_subscription->current_period_end);

                //save the subscription to database, add one month to ends_at.
                if($subscription_id){
                    // Create once per shop.
                    $attr = [
                        'shop_id' => $shop->id,
                    ];

                    $def = [
                        'will_cancel' => 'no',
                        'amount' => $amount,
                        'sub_id' => $subscription_id,
                        'package_type' => $package_type,
                        'details' => $name,
                        'ends_at' => $subscription_end,
                    ];
                    
                    // Create or update the subscription package for the given shop
                    $active_package = SubscriptionPackages::updateOrCreate($attr, $def);


                    //update shop available products
                    if($package_type === 'Basic'){
                        $shop_total_products = $shop->total_products;
                        $products_to_add = $total_products - $shop_total_products;
                        $shop->products_available = $products_to_add;
                        $shop->stripe_session = $session_id;
                        $shop->active = 'yes';
                        $shop->save();
                    }else{
                        $shop->stripe_session = $session_id;
                        $shop->products_available = 'unlimited';
                        $shop->active = 'yes';
                        $shop->save();
                    }

                    //increment referral funds, if referer exists
                    $refer_id = Referral::where('refered_id', $shop->user_id)->value('refer_id');
                    if($refer_id){
                        $refer_user = User::where('id', $refer_id)->increment('referral_balance', 5);
                    } 

                    return redirect()
                        ->route('shop_package_upgrade', $shop->id)
                        ->with('success', trans('general.successfully_upgraded_shop'));
                }
            }
        }
    }
    


}
