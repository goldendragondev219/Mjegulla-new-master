<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Products;
use App\Models\PaymentProviders;
use App\Models\Customer;
use App\Models\Shop;
use App\Models\Categories;
use App\Models\PurchasedPlugins;
use App\Models\Cart;

class WishlistController extends Controller
{
    public function add(Request $request)
    {
        $customer_id = auth()->guard('customers')->user()->id;
        $product = Products::where([
            'id' => $request->product_id,
            'shop_id' => $request->shop_id,
        ])->first();
        if($product){
            $wishlist = Wishlist::firstOrCreate([
                'user_id' => $customer_id,
                'shop_id' => $product->shop_id,
                'product_id' => $product->id,
            ]);
            if ($wishlist->wasRecentlyCreated) {
                // Wishlist record was just created
                return response()->json([
                    'message' => trans('general.product_added_to_wishlist'),
                    'added' => true,
            ]);
            } else {
                $wishlist->delete();
                return response()->json([
                    'message' => trans('general.product_removed_from_wishlist'),
                    'deleted' => true,
                ]);
            }
        }else{
            return response()->json(['message' => trans('general.there_was_an_error')]);
        }
    }



    public function myWishlist(Request $request, $subdomain = null)
    {
        $host = $request->getHost();
        $custom_domain = Shop::where('custom_domain', $host)->first();
        if ($custom_domain) {
            $subdomain = $custom_domain->my_shop_url;
        }

        $shop = Shop::where('my_shop_url', $subdomain)->firstOrFail();
        $categories = \App\Models\MenuItem::where('shop_id', $shop->id)->get();
        $plugins = PurchasedPlugins::where([
            'shop_id' => $shop->id,
            'user_id' => $shop->user_id,
        ])->get();


        $wishlist_count = Wishlist::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
            ])->count();

        $wishlist = Wishlist::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->get();

        $cart_count = Cart::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->count();

        //random 4 products for related products
        $related_products = Products::inRandomOrder()
        ->where('shop_id', $shop->id)
        ->where('deleted', 'no')
        ->take(4)
        ->get();
    

        return view('shops.theme_'.$shop->theme.'.wishlist',[
            'shop' => $shop,
            'categories' => $categories,
            'header_message' => $shop->header_message,
            'social_networks' => json_decode($shop->social_networks, true),
            'social_switch' => $shop->social_networks,
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'wishlist' => $wishlist,
            'cart_count' => $cart_count,
            'related_products' => $related_products,
        ]);
    }







}
