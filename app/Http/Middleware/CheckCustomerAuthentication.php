<?php

namespace App\Http\Middleware;

use Closure;

class CheckCustomerAuthentication
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
        if (!auth()->guard('customers')->check()) {
            return response()->json([
                'message' => trans('general.you_have_to_be_logged_in'),
                'authenticated' => false,
            ]);
        }
                
        return $next($request);
    }
}
