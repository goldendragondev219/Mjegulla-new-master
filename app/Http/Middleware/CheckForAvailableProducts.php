<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

use Closure;
use App\Models\Shop;

class CheckForAvailableProducts
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
        $shop = Shop::findOrFail(auth()->user()->managing_shop);
        if($shop->products_available == 0 && $shop->products_available !== 'unlimited'){
            if(Route::is('cj_dropshipping_import_product')){
                return response()->json([
                    'status' => false,
                    'message' => trans('general.upgrade_product_limit_reached')
                ]);
            }
            return redirect()->back()->with('error', trans('general.upgrade_product_limit_reached'));
        }
        return $next($request);
    }
}
