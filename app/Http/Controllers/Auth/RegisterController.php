<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\PaymentProviders;
use App\Models\Referral;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:50', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Validation of input data
        $validatedData = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:50|confirmed',
        ])->validate();
    
        // Flush the cache
        \Cache::flush();
    
        $secret_key = PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key');
        Stripe::setApiKey($secret_key);
    
        $customer = Customer::create([
            'email' => $validatedData['email'],
            'name' => $validatedData['name'],
        ]);
    
        $discord = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ];
    
        event(new \App\Events\DiscordBot('registration', $discord));
    
        $created_user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'customer_id' => $customer->id,
            'password' => Hash::make($validatedData['password']),
            'default_language' => 'sq',
        ]);
    
        if (Session::has('referral')) {
            $ref = new Referral();
            $ref->refer_id = Session::get('referral');
            $ref->refered_id = $created_user->id;
            $ref->save();
        }
    
        // Set the locale before returning
        // App::setLocale('sq');
        // session(['locale' => 'sq']);
        // app()->setLocale('sq');
    
        return $created_user;
    }
}
