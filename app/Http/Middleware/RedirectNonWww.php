<?php

namespace App\Http\Middleware;

use Closure;

class RedirectNonWww
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
        if (strpos($request->url(), 'www.') === 0) {
            dd('is www');
            $newUrl = preg_replace('/^https?:\/\/www\.(.*)$/i', 'https://$1', $request->url());
            return redirect($newUrl, 301);
        }
        return $next($request);
    }
}
