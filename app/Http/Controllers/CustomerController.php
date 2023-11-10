<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Customer as StripeCustomer;
use App\Models\PaymentProviders;
use App\Models\Customer;
use App\Models\Shop;
use App\Models\Categories;
use App\Models\PurchasedPlugins;
use App\Models\Wishlist;
use App\Models\Cart;

class CustomerController extends Controller
{


    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'shop_id' => 'required',
        ]);
    
        $credentials = array_merge($validatedData, ['shop_id' => $request->input('shop_id')]);
        
        if (Auth::guard('customers')->attempt($credentials)) {
            return response()->json([
                'authenticated' => true,
                'message' => trans('general.logged_in_successfully'),
            ]);
        } else {
            return response()->json([
                'authenticated' => false,
                'message' => trans('general.invalid_email_or_pass'),
            ]);
        }
    }


    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,NULL,id,shop_id,' . $request->input('shop_id'),
            'password' => 'required|min:8|confirmed|max:50',
            'shop_id' => 'required',
        ], [
            'email.unique' => trans('general.email_already_in_use'),
        ]);

        // Check if the customer is already authenticated
        if (Auth::guard('customers')->check()) {
            return response()->json([
                'authenticated' => true,
                'message' => trans('general.you_are_logged_in'),
            ]);
        }

        //initiate stripe
        $secret_key = PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key');
        Stripe::setApiKey($secret_key);
        

        $customer = new Customer;
        $customer->name = $validatedData['name'];
        $customer->email = $validatedData['email'];
        $customer->shop_id = $validatedData['shop_id'];
        $customer->password = Hash::make($validatedData['password']);

        //create stripe customer
        $stripe_customer = StripeCustomer::create([
            'email' => $customer->email,
            'name' => $customer->name,
        ]);

        $customer->customer_id = $stripe_customer->id;
        $customer->save();

        if($customer->save()){
            Auth::guard('customers')->login($customer);
            return response()->json([
                'authenticated' => true,
                'message' => trans('general.logging_in'),
            ]);
        }else{
            return response()->json([
                'authenticated' => false,
                'message' => trans('general.there_was_an_error'),
            ]);
        }
     
    }






}
