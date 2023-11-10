<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\App;

use Closure;
use Session;
use Illuminate\Support\Facades\Cache;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the language from the URL parameter (e.g., /lang/en or /lang/sq)
        $langFromUrl = $request->route('lang');

        // Set the locale if it's present in the URL parameter (e.g., /lang/en or /lang/sq)
        if ($langFromUrl && in_array($langFromUrl, ['en', 'sq'])) {
            App::setLocale($langFromUrl);
            session(['locale' => $langFromUrl]);
            app()->setLocale($langFromUrl);
            Session::put('locale', $langFromUrl);
        } else {
            // Check if the language is already cached
            if (Cache::has('user_locale')) {
                $userLocale = Cache::get('user_locale');
                App::setLocale($userLocale);
                session(['locale' => $userLocale]);
                app()->setLocale($userLocale);
                Session::put('locale', $userLocale);
            } else {
                try {
                    Session::put('locale', config('app.locale'));

                    // Define the available languages
                    $availableLangs = ['en', 'sq'];

                    // Check if the user's country is already cached
                    if (Cache::has('user_country')) {
                        $userCountry = Cache::get('user_country');
                    } else {
                        // Determine the user's real IP address behind Cloudflare
                        $userIp = $this->getUserRealIp($request);

                        // Query the IP-API to get the user's country code
                        $userCountry = dd($this->getUserCountryFromApi($userIp));

                        // Cache the user's country for one month
                        Cache::put('user_country', $userCountry, now()->addMonth());
                    }
                    
                    // If the user is from Albania, Kosovo, or Macedonia, set the language to "sq"
                    if (in_array($userCountry, ['Albania', 'Kosovo', 'Macedonia'])) {
                        App::setLocale('sq');
                        session(['locale' => 'sq']);
                        app()->setLocale('sq');
                        Session::put('locale', 'sq');
                    } else {
                        // Check the user's browser's accepted languages
                        $userLangs = explode(',', $request->server('HTTP_ACCEPT_LANGUAGE'));

                        foreach ($userLangs as $userLang) {
                            $userLang = trim($userLang);
                            $lang = substr($userLang, 0, 2);
                            if (in_array($lang, $availableLangs)) {
                                App::setLocale($lang);
                                session(['locale' => $lang]);
                                app()->setLocale($lang);
                                Session::put('locale', $lang);
                                break;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Handle exceptions if necessary
                }
            }
        }

        return $next($request);
    }

    // Implement a method to get the user's real IP address behind Cloudflare
    private function getUserRealIp($request)
    {
        // Check if the request has the Cloudflare headers
        if ($request->hasHeader('CF-Connecting-IP')) {
            return $request->header('CF-Connecting-IP');
        }

        // If not, fall back to the regular IP
        return $request->ip();
    }

    // Implement a method to query the IP-API to get the user's country code
    private function getUserCountryFromApi($userIp)
    {
        // Build the API URL
        $apiUrl = "http://ip-api.com/json/" . $userIp;

        // Make a GET request to the API and decode the JSON response
        $apiResponse = file_get_contents($apiUrl);
        $apiData = json_decode($apiResponse);

        // Check if the API returned a valid response and extract the country code
        if ($apiData && $apiData->status === 'success') {
            return $apiData->countryCode;
        }

        // Default to 'Unknown' if the API didn't return a valid response
        return 'Unknown';
    }
}
