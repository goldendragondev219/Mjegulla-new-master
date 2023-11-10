<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Price;
use Stripe\PaymentMethod;
use App\Models\PaymentProviders;
use App\Models\User;
use Auth;

class CardController extends Controller
{
    public function getCard()
    {
        Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));

        // Retrieve all payment methods for the current user
        $paymentMethods = PaymentMethod::all([
            'customer' => auth()->user()->customer_id,
            'type' => 'card',
        ]);

        // Loop through the payment methods to check for the default payment method
        $defaultPaymentMethod = null;
        foreach ($paymentMethods as $paymentMethod) {
            if ($paymentMethod->id === auth()->user()->default_payment_method) {
                $paymentMethod->default_payment_method = true;
                $defaultPaymentMethod = $paymentMethod->id;
            }
            // Get the customer name from the payment method's billing details
            $customerName = $paymentMethod->billing_details->name;
            $paymentMethod->name = $customerName;
        }

        // Add the default payment method ID to the payment methods object
        $paymentMethods->default_payment_method = $defaultPaymentMethod;

        // Return the payment methods object
        return $paymentMethods;


    }
    

    public function makeDefault($payment_method_id)
    {
        Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));
    
        // Get the customer object
        $customer = Customer::retrieve(auth()->user()->customer_id);
    
        // Retrieve the payment method and verify that it belongs to the authenticated user
        $payment_method = \Stripe\PaymentMethod::retrieve($payment_method_id);
        if ($payment_method->customer !== $customer->id) {
            return redirect()->back()->withErrors(trans('general.invalid_payment_method'));
        }
    
        // Attach the payment method to the customer
        $payment_method->attach(['customer' => $customer->id]);
    
        // Set the payment method as the default source
        $customer->invoice_settings = ['default_payment_method' => $payment_method_id];
        $customer->save();
        auth()->user()->update(['default_payment_method' => $payment_method_id]);
    
        // Return a success message or redirect back to the payment methods page
        return redirect()->back()->with('success', trans('general.default_card_updated_success'));
    }
    


    public function deleteCard($payment_method_id)
    {
        Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));

        // Get the customer object
        $customer = Customer::retrieve(auth()->user()->customer_id);

        // Retrieve the payment method and verify that it belongs to the authenticated user
        $payment_method = \Stripe\PaymentMethod::retrieve($payment_method_id);
        if ($payment_method->customer !== $customer->id) {
            return redirect()->back()->withErrors(trans('general.invalid_payment_method'));
        }

        // Detach the payment method from the customer
        $payment_method->detach();

        // Return a success message or redirect back to the payment methods page
        return redirect()->back()->with('success', trans('general.card_deleted_successfully'));
    }

    
    
}
