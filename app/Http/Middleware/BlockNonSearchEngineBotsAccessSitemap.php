<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BlockNonSearchEngineBotsAccessSitemap
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request is coming from a search engine bot
        $userAgent = $request->header('User-Agent');
        if ($userAgent && preg_match('/bot|crawl|slurp|spider/i', $userAgent)) {
            return $next($request);
        }

        // If the request is not from a search engine bot, return a 404 response
        abort(404);
    }
}
