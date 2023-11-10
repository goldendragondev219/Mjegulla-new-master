<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Price;
use Stripe\PaymentMethod;
use Stripe\Product;
use Stripe\Checkout\Session;
use App\Models\PaymentProviders;
use App\Models\User;
use App\Models\Shop;
use App\Models\SubscriptionPackages;
use App\Models\ShippingRates;
use Auth;
use Carbon\Carbon;
use App\Models\Referral;



$private_key = PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key');
\Stripe\Stripe::setApiKey($private_key);

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function handleStripeRedirect(Request $request)
    {
        // Check if a Stripe Checkout session ID was provided
        if ($request->has('session_id')) {
            // Retrieve the Stripe Checkout session ID from the request
            $sessionId = $request->input('session_id');
            // Retrieve the Stripe Checkout session using the ID
            $session = Session::retrieve($sessionId);

            // Check the payment status of the session
            if ($session->payment_status === 'paid') {
                // Payment successful, display success message

                $metadata = $session->metadata;
                //create the shop
                $shop_exist = Shop::where('my_shop_url', $metadata['my_shop_url'])->first();
                if(!$shop_exist){
                    $auth_user = auth()->user();
                    $metadata = $session->metadata;
                    $shop = new Shop();
                    $shop->user_id = $metadata['user_id'];
                    $shop->shop_name = $metadata['shop_name'];
                    $shop->shop_description = $metadata['shop_description'];
                    $shop->shop_seo_keywords = $metadata['shop_seo_keywords'];
                    $shop->my_shop_url = $metadata['my_shop_url'];
                    $shop->shop_image_url = 'https://cdn.mjegulla.com/store/images/'.$metadata['shop_logo_url'];
                    $shop->stripe_session = $sessionId;
                    $shop->products_available = $metadata['products_available'];
                    if($metadata['store_type'] == 'dropshipping'){
                        $shop_payment_methods = [
                            'credit_card' => true,
                            'cash' => false,
                        ];
                    }else{
                        $shop_payment_methods = [
                            'credit_card' => false,
                            'cash' => true,
                        ];
                    }
                    
                    $json_payment_methods = json_encode($shop_payment_methods);
                    $shop->payment_methods = $json_payment_methods;
                    $shop->full_name = $metadata['full_name'];
                    $shop->company = $metadata['company'];
                    $shop->city = $metadata['city'];
                    $shop->address = $metadata['address'];
                    $shop->phone = $metadata['phone'];
                    $shop->store_type = $metadata['store_type'];
                    $shop->save();


                    //discord data shop create
                    $discord = array(
                        'name' => 'PAID - '.$shop->shop_name,
                        'email' => auth()->user()->email,
                        'shop_url' => 'https://'.$shop->my_shop_url.'.mjegulla.com', 
                    );
                    event(new \App\Events\DiscordBot('shop_create', $discord));

                    // retrieve the customer's default payment method and update in the DB
                    Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));
                    $payment_methods = \Stripe\PaymentMethod::all([
                        'customer' => $auth_user->customer_id,
                        'type' => 'card',
                    ]);
                    $default_payment = end($payment_methods->data)->id;
                    auth()->user()->update(['default_payment_method' => $default_payment]);
                    $sub_packages = new SubscriptionPackages();
                    $sub_packages->shop_id = $shop->id;
                    $sub_packages->package_type = $metadata['package'];
                    $sub_packages->details = $metadata['full_details'];
                    $sub_packages->amount = $session->amount_total/100;
                    $sub_packages->ends_at = now()->addMonth()->format('Y-m-d H:i:s');
                    $sub_packages->will_cancel = 'no';
                    $sub_packages->sub_id = $session->subscription;
                    $sub_packages->save();

                    //create automatic shipping rates
                    if($metadata['store_type'] == 'dropshipping'){
                        $shippingRates = (new ShippingRatesController)->AutoCreate($shop->id);
                    }else{
                        $shippingRates = (new ShippingRatesController)->LocalSelling($shop->id);
                    }


                    //check if user has been referred, and add 5EUR to referrer referral_balance
                    $refer_id = Referral::where('refered_id', $auth_user->id)->value('refer_id');
                    if($refer_id){
                        $refer_user = User::where('id', $refer_id)->increment('referral_balance', 5);
                    }
                    

                }
                return view('home')->with('success', true);
            } else {
                // Payment failed, display error message
                return view('home')->with('error', true);
            }
        } else {
            // No session ID provided, return home.
            return view('home');
        }
    }
    

    public function viewProfile()
    {
        return view('editProfile');
    }

    public function profileUpdate(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:4|max:50',
        ]);
        $name = $request->name;
        $req = auth()->user()->update(['name' => $name]);
        if($req){
            return redirect()->back()->with('success', trans('general.profile_updated_successfully'));
        }else{
            return redirect()->back()->withErrors(trans('general.there_was_an_error'));
        }
    }



    public function changePasswordView()
    {
        return view('change_password');
    }


    public function changePassword(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user
        
        // Validate the incoming request data
        $validatedData = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'max:100', 'confirmed'],
        ]);
        
        // Check if the provided current password matches the user's actual password
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return redirect()->back()->withErrors(trans('general.provided_current_pass_incorrect'));
        }
        
        // Hash the new password
        $hashedPassword = Hash::make($validatedData['new_password']);
        
        // Update the user's password
        $user->password = $hashedPassword;
        $user->save();
        return redirect()->back()->with('success', trans('general.password_updated_successfully'));
    }
    

    
    public function billingView(){

        // Get the customer's active subscriptions
        $subscriptions = Subscription::all([
            'customer' => auth()->user()->customer_id,
            'status' => 'all'
        ]);
        $activeSubscriptions = [];
        foreach ($subscriptions as $subscription) {
            $amount = $subscription->plan->amount;
            $productId = $subscription->plan->product;
            $product = Product::retrieve($productId);
            $productName = $product->name;
            $ends_at = date('Y-m-d H:i:s', $subscription->current_period_end);
            $sub_id = $subscription->id;
            $plan_id = $subscription->plan->id;
            $status = $subscription->status;
            $will_be_cancelled = $subscription->cancel_at_period_end;
            
            $activeSubscriptions[] = [
                'product_name' => $productName,
                'amount' => $amount,
                'ends_at' => $ends_at,
                'sub_id' => $sub_id,
                'plan_id' => $plan_id,
                'status' => $status,
                'will_be_cancelled' => $will_be_cancelled,
            ];
        }
         // Retrieve the card data using the getCard method of the CardController
        $cards = (new CardController)->getCard();
        return view('billing_view',[
            'subscriptions' => $activeSubscriptions,
            'cards' => $cards,
        ]);
    }


    

}
