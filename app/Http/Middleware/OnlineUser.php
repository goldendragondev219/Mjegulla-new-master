<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Carbon\Carbon;


use Closure;
use App\Models\OnlineUsers;
use App\Models\Shop;

class OnlineUser
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

        //$request = Request::capture();
        $host = $request->getHost();
        
        // Extract the domain name from the host name
        $domain = implode('.', array_slice(explode('.', $host), -2));
        
        // Check if the domain name is different from the host name
        if ($domain !== $host) {
            // Subdomain is present
            // Do something...

        } else {
            if($host !== config('session.domain')){
                $custom_domain = Shop::where('custom_domain', $host)->where('custom_domain_activated', '1')->first();
                $subdomain = $custom_domain->my_shop_url;
            }else{
                return $next($request);    
            }
        }
        

        //check customer user id.
        if(isset(auth()->guard('customers')->user()->id)){
            $user_id = auth()->guard('customers')->user()->id;
        }else{
            $user_id = 0;
        }


        //referer
        $referer = $request->headers->get('referer');
        if ($referer && parse_url($referer, PHP_URL_HOST) != $_SERVER['HTTP_HOST']) {
            // Referer header is set and coming from an external domain
            $referer_url = $referer;
        }else{
            $referer_url = 'direct';
        }


        //get customer ip.
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $client_ip = $_SERVER['REMOTE_ADDR'];
        }

        $host = $request->getHost();
        $custom_domain = Shop::where('custom_domain', $host)->where('custom_domain_activated', '1')->first();
        if ($custom_domain) {
            $subdomain = $custom_domain->my_shop_url;
        }else{
            // Get the subdomain
            $subdomain = explode('.', $host)[0];
        }

        if(isset($subdomain) && $subdomain !== 'www'){
            //subdomain isset.
            $shop = Shop::where('my_shop_url', $subdomain)->firstOrFail();
            OnlineUsers::updateOrCreate(
                ['ip' => $client_ip],
                [
                    'user_id' => $user_id,
                    'shop_id' => $shop->id,
                    'referer' => $referer_url,
                    'ip' => $client_ip,
                    'online_at' => Carbon::now()->addMinutes(2),
                ]
            );
        }else{
            //shop has its own domain or is using www.
            $newUrl = preg_replace('/^https?:\/\/www\.(.*)$/i', 'https://$1', $request->url());
            return redirect($newUrl, 301);
        }

        return $next($request);
    }
}
