<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Shop;


class SetSubdomainSessionName
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $subdomain = $this->getSubdomain($host);
        if ($subdomain) {
            config(['session.domain' => "$subdomain." . config('session.domain')]);
            config(['session.name' => "{$subdomain}_session"]);
        } else {
            if($host !== config('session.domain')){
                //check if custom domain exist on our DB.
                $shop = Shop::where('custom_domain', $host)->where('custom_domain_activated', '1')->first();
                if($shop){
                    config(['session.domain' => $host]);
                    config(['session.name' => "{$host}_session"]);
                }else{
                    abort(403);
                }

            }else{
                config(['session.domain' => config('session.domain')]);
                config(['session.name' => "base_session"]);                
            }

        }

        return $next($request);
    }

    protected function getSubdomain($hostname)
    {
        $parts = explode('.', $hostname);

        if (count($parts) >= 3 && $parts[0] !== 'www') {
            return $parts[0];
        }

        return null;
    }
}
