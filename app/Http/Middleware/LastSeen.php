<?php

namespace App\Http\Middleware;

use Closure;
use \App\User;
use Carbon\Carbon;

class LastSeen
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
        if(auth()->check()){
            auth()->user()->update([
                'last_seen' => now()
            ]);
        }
        return $next($request);
    }
}
