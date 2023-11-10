<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Shop;
use App\Models\Products;
use App\Models\Categories;
use App\Models\ProductImages;
use App\Models\Variants;
use App\Models\PurchasedPlugins;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Collections;
use App\Models\CollectionProducts;
use App\Models\MenuItem;

class SubdomainController extends Controller
{
    public function index(Request $request, $subdomain = null)
    {
        $currentDomain = request()->getHost();
        $custom_domain = Shop::where('custom_domain', $currentDomain)->first();
    
        if ($custom_domain) {
            $subdomain = $custom_domain->my_shop_url;
        }
        $shop = Shop::where('my_shop_url', $subdomain)->firstOrFail();

        //exclude collection products
        $products = Products::where('shop_id', $shop->id)
                    ->where('deleted', 'no')
                    ->whereNotExists(function ($query) use ($shop) {
                        $query->select('product_id')
                            ->from('collection_products')
                            ->whereRaw('products.id = collection_products.product_id')
                            ->where('collection_products.shop_id', $shop->id);
                    })
                    ->orderByDesc('created_at')
                    ->paginate(12);

        $categories = MenuItem::where('shop_id', $shop->id)->get();//Categories::where('shop_id', $shop->id)->get();
        $collections = Collections::where('shop_id', $shop->id)->get();
        $plugins = PurchasedPlugins::where([
            'shop_id' => $shop->id,
            'user_id' => $shop->user_id,
        ])->get();

        if(auth()->guard('customers')->check()){
            $wishlist_count = Wishlist::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
            $cart_count = Cart::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
        }else{
            $wishlist_count = 0;
            $cart_count = 0;
        }

        return view('shops.theme_'.$shop->theme.'.index',[
            'shop' => $shop,
            'products' => $products,
            'categories' => $categories,
            'featured_switch' => $shop->home_featured_details,
            'featured_details' => json_decode($shop->home_featured_details, true),
            'header_message' => $shop->header_message,
            'social_switch' => $shop->social_networks,
            'social_networks' => json_decode($shop->social_networks, true),
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'cart_count' => $cart_count,
            'collections' => $collections,
        ]);

    }



    public function product(Request $request, $url)
    {
        $host = $request->getHost();

        $custom_domain = Shop::where('custom_domain', $host)->first();
    
        if ($custom_domain) {
            $subdomain = $custom_domain->my_shop_url;
        }else{
            // Get the subdomain
            $subdomain = explode('.', $host)[0];
            // Get the domain name
            $domain = implode('.', array_slice(explode('.', $host), -2));
        }


        $shop = Shop::where('my_shop_url', $subdomain)->firstOrFail();
        $product = Products::where(['product_url' => $request->url, 'shop_id' => $shop->id, 'deleted' => 'no'])->firstOrFail();
        $categories = MenuItem::where('shop_id', $shop->id)->get();
        $product_images = optional(ProductImages::where('product_id', $product->id)->orderBy('id','DESC')->get())->toArray();
        $product_variants = optional(Variants::select('size','price','color','quantity','quantity_left')->where('product_id', $product->id)->where('deleted', 'no')->get())->toArray();



        $plugins = PurchasedPlugins::where([
            'shop_id' => $shop->id,
            'user_id' => $shop->user_id,
        ])->get();

        //random 4 products for related products
        $related_products = Products::inRandomOrder()
        ->where('id', '!=', $product->id)
        ->where('shop_id', $shop->id)
        ->where('deleted', 'no')
        ->take(4)
        ->get();
    

        if(auth()->guard('customers')->check()){
            $wishlist_count = Wishlist::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
            $cart_count = Cart::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
        }else{
            $wishlist_count = 0;
            $cart_count = 0;
        }


        // Remove HTML tags from product description
        $prod_desc = strip_tags($product->product_description);
       
        return view('shops.theme_'.$shop->theme.'.product', [
            'product' => $product,
            'shop' => $shop,
            'shop_url' => ($shop->custom_domain_activated) ? "https://{$shop->custom_domain}" : "https://{$shop->my_shop_url}." . parse_url(config('app.url'), PHP_URL_HOST),
            'categories' => $categories,
            'product_description' => $prod_desc,
            'product_images' => $product_images,
            'product_variants' => $product_variants,
            'related_products' => $related_products,
            'header_message' => $shop->header_message,
            'social_switch' => $shop->social_networks,
            'social_networks' => json_decode($shop->social_networks, true),
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'cart_count' => $cart_count,
        ]);
    }





    //get category products
    public function category(Request $request)
    {
        $host = $request->getHost();

        $custom_domain = Shop::where('custom_domain', $host)->first();
    
        if ($custom_domain) {
            $subdomain = $custom_domain->my_shop_url;
        }else{
            // Get the subdomain
            $subdomain = explode('.', $host)[0];
            // Get the domain name
            $domain = implode('.', array_slice(explode('.', $host), -2));
        }

        $shop = Shop::where('my_shop_url', $subdomain)->firstOrFail();
        $cat = MenuItem::where(['link' => $request->category, 'shop_id' => $shop->id])->firstOrFail();
        $categories = MenuItem::where('shop_id', $shop->id)->get();

        $products = Products::where(['product_category' => $cat->id, 'deleted' => 'no'])->orderBy('id', 'DESC')->paginate(12);

        $plugins = PurchasedPlugins::where([
            'shop_id' => $shop->id,
            'user_id' => $shop->user_id,
        ])->get();

        if(auth()->guard('customers')->check()){
            $wishlist_count = Wishlist::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
            $cart_count = Cart::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
        }else{
            $wishlist_count = 0;
            $cart_count = 0;
        }

        return view('shops.theme_'.$shop->theme.'.category',[
            'shop' => $shop,
            'products' => $products,
            'categories' => $categories,
            'category_name' => $cat->label,
            'featured_switch' => $shop->home_featured_details,
            'featured_details' => json_decode($shop->home_featured_details, true),
            'header_message' => $shop->header_message,
            'social_switch' => $shop->social_networks,
            'social_networks' => json_decode($shop->social_networks, true),
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'cart_count' => $cart_count,
        ]);


    }



    //get subcategory products
    public function SubCategory(Request $request)
    {
        $host = $request->getHost();

        $custom_domain = Shop::where('custom_domain', $host)->first();
    
        if ($custom_domain) {
            $subdomain = $custom_domain->my_shop_url;
        }else{
            // Get the subdomain
            $subdomain = explode('.', $host)[0];
            // Get the domain name
            $domain = implode('.', array_slice(explode('.', $host), -2));
        }

        $shop = Shop::where('my_shop_url', $subdomain)->firstOrFail();
        $cat = MenuItem::where(['link' => $request->category, 'shop_id' => $shop->id])->firstOrFail();
        $categories = MenuItem::where('shop_id', $shop->id)->get();

        $products = Products::where(['admin_menu_item_id' => $cat->id, 'deleted' => 'no'])->orderBy('id', 'DESC')->paginate(12);

        $plugins = PurchasedPlugins::where([
            'shop_id' => $shop->id,
            'user_id' => $shop->user_id,
        ])->get();

        if(auth()->guard('customers')->check()){
            $wishlist_count = Wishlist::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
            $cart_count = Cart::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
        }else{
            $wishlist_count = 0;
            $cart_count = 0;
        }

        return view('shops.theme_'.$shop->theme.'.sub_category',[
            'shop' => $shop,
            'products' => $products,
            'categories' => $categories,
            'category_name' => $cat->label,
            'featured_switch' => $shop->home_featured_details,
            'featured_details' => json_decode($shop->home_featured_details, true),
            'header_message' => $shop->header_message,
            'social_switch' => $shop->social_networks,
            'social_networks' => json_decode($shop->social_networks, true),
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'cart_count' => $cart_count,
        ]);
    
    
    }


    //get collection products
    public function collection(Request $request)
    {
        $host = $request->getHost();

        $custom_domain = Shop::where('custom_domain', $host)->first();
    
        if ($custom_domain) {
            $subdomain = $custom_domain->my_shop_url;
        }else{
            // Get the subdomain
            $subdomain = explode('.', $host)[0];
            // Get the domain name
            $domain = implode('.', array_slice(explode('.', $host), -2));
        }

        $shop = Shop::where('my_shop_url', $subdomain)->firstOrFail();
        $categories = MenuItem::where('shop_id', $shop->id)->get();

        $collection = Collections::where('slug', $request->collection)->where('shop_id', $shop->id)->first();
        $products = CollectionProducts::where('collection_id', $collection->id)->get();

        $plugins = PurchasedPlugins::where([
            'shop_id' => $shop->id,
            'user_id' => $shop->user_id,
        ])->get();

        if(auth()->guard('customers')->check()){
            $wishlist_count = Wishlist::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
            $cart_count = Cart::where([
                'user_id' => auth()->guard('customers')->user()->id,
                'shop_id' => $shop->id,
                ])->count();
        }else{
            $wishlist_count = 0;
            $cart_count = 0;
        }

        return view('shops.theme_'.$shop->theme.'.collection',[
            'shop' => $shop,
            'products' => $products,
            'categories' => $categories,
            'collection_name' => $collection->name,
            'featured_switch' => $shop->home_featured_details,
            'featured_details' => json_decode($shop->home_featured_details, true),
            'header_message' => $shop->header_message,
            'social_switch' => $shop->social_networks,
            'social_networks' => json_decode($shop->social_networks, true),
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'cart_count' => $cart_count,
        ]);
    
    
    }


}
