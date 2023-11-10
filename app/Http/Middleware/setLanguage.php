<?php

namespace App\Http\Middleware;

use Closure;

class setLanguage
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
        $locale = null;

        // Get the locale from the URL, if present
        $locale = $request->route('locale');

        // If not present, check if the locale is set in the session
        if (! $locale && session()->has('locale')) {
            $locale = session()->get('locale');
        }

        // If not, check if the user is authenticated and has a default language set
        if (! $locale && auth()->check()) {
            $user = auth()->user();
            if ($user->default_language) {
                $locale = $user->default_language;
            }
        }

        // If the locale is still not set, set it to the default app locale
        if (! $locale) {
            //set shops default language to english
            if($request->getHost() !== config('app.session_domain')){
                $locale = 'en';
            }else{
                $locale = config('app.locale');    
            }
        }

        // Set the app locale to the determined locale
        app()->setLocale($locale);

        return $next($request);
    }
}
