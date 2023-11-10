<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Laravel\Socialite\Facades\Socialite;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\PaymentProviders;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated()
    {
        // Flush the cache
        \Cache::flush();
        $default_lang = auth()->user()->default_language;
        App::setLocale($default_lang);
        session(['locale' => $default_lang]);
        app()->setLocale($default_lang);
        // Redirect to the home page
        return redirect('/home');
    }



    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    public function loginWithGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
    
        $existingUser = User::where('email', $user->email)->first();
        
        if ($existingUser) {
            \Cache::flush();

            //if loggin with google, and have been not verified, verify it.
            if($existingUser->email_verified_at == NULL){
                $existingUser->email_verified_at = Carbon::now();
                $existingUser->save();
            }
            if(Auth::login($existingUser)){
                return redirect()->intended(url()->previous());
            }
            //Auth::login($existingUser);
        } else {
            $secret_key = PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key');
            Stripe::setApiKey($secret_key);
            
            $customer = Customer::create([
                'email' => $user['email'],
                'name' => $user['name'],
            ]);
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt(Str::random(16)),
                'customer_id' => $customer->id,
                'default_language' => 'sq',
            ]);
            event(new \App\Events\DiscordBot('registration', $user));
            \Cache::flush();
            App::setLocale('sq');
            session(['locale' => 'sq']);
            app()->setLocale('sq');
            if(Auth::login($newUser)){
                return redirect()->intended(url()->previous());
            }
            //Auth::login($newUser);
        }
    
        return redirect('/home');
    }


}
