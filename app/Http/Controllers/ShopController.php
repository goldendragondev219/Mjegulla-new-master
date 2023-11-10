<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Shop;
use App\Models\StoreImpressions;
use App\Models\Variants;
use App\Models\Plugins;
use App\Models\PurchasedPlugins;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ProductImages;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Categories;
use App\Models\CustomerBilling;
use App\Models\ShippingRates;
use App\Models\SubscriptionPackages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Models\PaymentProviders;
use Stripe\Stripe;
use Stripe\Plan;
use Stripe\Checkout\Session;



class ShopController extends Controller
{
    public function newView()
    {
        return view('newShop');
    }


    public function create(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|min:4|max:100|',
            //'company' => 'nullable|string|min:4|max:100|',
            'city' => 'required|string|min:4|max:100|',
            'address' => 'required|string|min:4|max:190|',
            'phone' => 'required|string|min:4|max:190|',
            'shop_name' => 'required|string|min:4|max:191|unique:shops',
            'my_shop_url' => 'required|string|min:4|max:100|regex:/^(?!-)(?!.*-$)[a-zA-Z0-9-]+$/|unique:shops',
            'shop_description' => 'required|string|min:10|max:200',
            'shop_seo_keywords' => 'string|min:4|max:200',
            'image' => 'required|image|mimes:jpeg,png,jpg,JPG|max:10240',
            'subscription' => 'required|in:free,basic,premium',
            'store_type' => 'required|in:dropshipping,local_store',
        ]);
        if(auth()->check()){

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::uuid() . '_' . $image->getClientOriginalName();
                $uploaded = Storage::disk('s3')->put('store/images/' . $filename, file_get_contents($image), 'public');
              
                // Check if the file was successfully uploaded
                if ($uploaded) {
                  //return response()->json(['filename' => $filename]);
                } else {
                  // Handle the case where the file was not uploaded
                  return redirect()->back()->withErrors(trans('general.file_upload_fail'));
                }
            }


            if($request->subscription !== 'free')
            {
                $createSubscription = (new Subscriptions)->createSubscription($request,$filename);
            }else{
                $shop_conn = Shop::where(['user_id' => auth()->user()->id, 'stripe_session' => NULL])->count();
                if($shop_conn){
                    return redirect()->back()->withErrors(trans('general.shop_creation_limit_reached'));
                }
            }


            $shop = new Shop();
            $shop->user_id = auth()->user()->id;
            $shop->shop_name = $request->shop_name;
            $shop->shop_description = $request->shop_description;
            $shop->shop_seo_keywords = $request->shop_seo_keywords;
            $shop->my_shop_url = $request->my_shop_url;
            $shop->shop_image_url = 'https://cdn.mjegulla.com/store/images/'.$filename;

            //Activate/Deactivate credit card based on store type
            if($request->store_type == 'dropshipping'){
                $shop_payment_methods = [
                    'credit_card' => true,
                    'cash' => false,
                ];
            }

            if($request->store_type == 'local_store'){
                $shop_payment_methods = [
                    'credit_card' => false,
                    'cash' => true,
                ];
            }

            $json_payment_methods = json_encode($shop_payment_methods);
            $shop->payment_methods = $json_payment_methods;
            $shop->full_name = $request->full_name;
            $shop->company = 'not required'; //$request->company;
            $shop->city = $request->city;
            $shop->address = $request->address;
            $shop->phone = $request->phone;
            $shop->active = 'yes';
            $shop->store_type = $request->store_type;
            $shop->save();
            if ($shop->save()) {
                //create shopping rates
                if($shop->store_type == 'dropshipping'){
                    $shippingRates = (new ShippingRatesController)->AutoCreate($shop->id);
                }

                if($shop->store_type == 'local_store'){
                    $shippingRates = (new ShippingRatesController)->LocalSelling($shop->id);
                }
                

                //discord data shop create
                $discord = array(
                    'name' => $shop->shop_name,
                    'email' => auth()->user()->email,
                    'shop_url' => 'https://'.$shop->my_shop_url.'.mjegulla.com', 
                );
                event(new \App\Events\DiscordBot('shop_create', $discord));


                return redirect('/home')->with('success', trans('general.shop_created_successfully'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }
        }
    }



    public function manageShop()
    {
        $id = auth()->user()->managing_shop;
        if(auth()->check()){
            //check if shop belongs to the user 
            $shop = Shop::where([
                'id' => $id,
                'user_id' => auth()->user()->id
            ])->first();
            
            if($shop){
                //save for one month in cache
                // Cache::put('shop_id', $shop->id, 2592000);
                
                auth()->user()->update(['managing_shop' => $shop->id]);
                return view('manageShop', [
                    'id' => $id,
                    'shop_id' => $shop->id,
                    'name' => $shop->shop_name,
                    'image_url' => $shop->shop_image_url,
                    'description' => $shop->shop_description,
                    'seo_keywords' => $shop->shop_seo_keywords,
                    'header_message' => $shop->header_message,
                    'social_networks' => json_decode($shop->social_networks, true),
                    'header_section' => json_decode($shop->home_featured_details, true),
                ]);
            }else{
                abort(401, 'Unauthorized');    
            }
        }else{
            abort(401, 'Unauthorized');
        }  
    }




    public function update(Request $request, $id)
    {
        if(auth()->check()){
            $data = $request->validate([
                'shop_name' => 'string|min:4|max:191',
                'shop_description' => 'required|string|min:10|max:200',
                'shop_seo_keywords' => 'string|min:4|max:200',
                'image' => 'image|mimes:jpeg,png,jpg,JPG|max:2048',
                'home_featured_image' => 'image|mimes:jpeg,png,jpg,JPG,gif|max:2048',
                'shop_front_page_featured_image_title' => 'min:0|max:100',
                'shop_front_page_featured_image_description' => 'min:0|max:500',
                'shop_front_page_featured_image_button_name' => 'min:0|max:30',
                'shop_front_page_featured_image_button_call_url' => 'nullable|url|min:0|max:100',
                'instagram_url' => 'nullable|url|min:0|max:100',
                'facebook_url' => 'nullable|url|min:0|max:100',
                'tiktok_url' => 'nullable|url|min:0|max:100',
                'pinterest_url' => 'nullable|url|min:0|max:100',
                'twitter_url' => 'nullable|url|min:0|max:100',
                'phone_nr' => 'nullable|string|min:0|max:30',
                'address' => 'nullable|string|min:0|max:200',
                'header_message' => 'min:0|max:80',
            ]);

            //check if shop belongs to the user 
            $shop = Shop::where([
                'id' => $id,
                'user_id' => auth()->user()->id
            ])->first();

            if($shop){
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = Str::uuid() . '_' . $image->getClientOriginalName();
                    $uploaded = Storage::disk('s3')->put('store/images/' . $filename, file_get_contents($image), 'public');
                  
                    // Check if the file was successfully uploaded
                    if ($uploaded) {
                      //return response()->json(['filename' => $filename]);
                    } else {
                      // Handle the case where the file was not uploaded
                      return redirect()->back()->withErrors(trans('general.file_upload_fail'));
                    }
                }

                //header featured image
                $home_featured_image = null;
                if ($request->hasFile('home_featured_image')) {
                    $image = $request->file('home_featured_image');
                    $home_featured_image = Str::uuid() . '_' . $image->getClientOriginalName();
                    $uploaded_f = Storage::disk('s3')->put('store/images/' . $home_featured_image, file_get_contents($image), 'public');
                  
                    // Check if the file was successfully uploaded
                    if ($uploaded_f) {
                      //return response()->json(['filename' => $filename]);
                    } else {
                      // Handle the case where the file was not uploaded
                      return redirect()->back()->withErrors(trans('general.file_upload_fail'));
                    }
                }
                  

                // Retrieve existing header section data from the database
                $existing_header_section = $shop->home_featured_details;

                // Convert the JSON string to an associative array
                if (!empty($existing_header_section)) {
                    $featured_header_section = json_decode($existing_header_section, true);
                } else {
                    $featured_header_section = [];
                }

                // Update the header section data
                $featured_header_section['title'] = $request->shop_front_page_featured_image_title;
                $featured_header_section['description'] = $request->shop_front_page_featured_image_description;
                $featured_header_section['button_name'] = $request->shop_front_page_featured_image_button_name;
                $featured_header_section['button_url'] = $request->shop_front_page_featured_image_button_call_url;
                if(!$request->hasFile('home_featured_image')){
                    $featured_header_section['featured_image'] = null;
                }

                if ($home_featured_image !== null) {
                    $featured_header_section['featured_image'] = 'https://cdn.mjegulla.com/store/images/'.$home_featured_image;
                } else if (!empty($featured_header_section) && array_key_exists('featured_image', $featured_header_section)) {
                    $home_featured_image = $featured_header_section['featured_image'];
                }

                //if featured image got removed via X button
                if($request->header_featured_img_removed === '1'){
                    $featured_header_section['featured_image'] = null;
                }

                // Save the updated header section data to the database
                if (empty($featured_header_section)) {
                    $shop->home_featured_details = null;
                } else {
                    $shop->home_featured_details = json_encode($featured_header_section);
                }

                //set header message
                $shop->header_message = $request->header_message;

                //set social networks
                $social_networks = [];

                if (!empty($request->instagram_url)) {
                    $social_networks['instagram'] = $request->instagram_url;
                }

                if (!empty($request->tiktok_url)) {
                    $social_networks['tiktok'] = $request->tiktok_url;
                }

                if (!empty($request->pinterest_url)) {
                    $social_networks['pinterest'] = $request->pinterest_url;
                }

                if (!empty($request->twitter_url)) {
                    $social_networks['twitter'] = $request->twitter_url;
                }
                
                if (!empty($request->facebook_url)) {
                    $social_networks['facebook'] = $request->facebook_url;
                }

                if (!empty($request->phone_nr)) {
                    $social_networks['phone'] = $request->phone_nr;
                }
                
                if (!empty($request->address)) {
                    $social_networks['address'] = $request->address;
                }

                $shop->social_networks = $social_networks;

                //save the shop data
                $shop->save();

                
                //featured header
                $isEmpty = true;
                foreach ($featured_header_section as $property => $value) {
                    if (!empty($value) || $value !== null) {
                        $isEmpty = false;
                        break;
                    }
                }
                if ($isEmpty) {
                    // Every property is empty or null
                    $shop->home_featured_details = 'off';
                    $shop->save();
                } else {
                    $shop->home_featured_details = $featured_header_section;
                    $shop->save();
                }
                
                //social networks
                $isEmpty = true;
                foreach ($social_networks as $property => $value) {
                    if (!empty($value) || $value !== null) {
                        $isEmpty = false;
                        break;
                    }
                }
                if ($isEmpty) {
                    // Every property is empty or null
                    $shop->social_networks = 'off';
                    $shop->save();
                } else {
                    $shop->social_networks = $social_networks;
                    $shop->save();
                }


                $save = Shop::where([
                    'id' => $id,
                    'user_id' => auth()->user()->id
                ]);
                
                if (isset($filename)) {
                    $save->update([
                        'shop_name' => $request->shop_name,
                        'shop_description' => $request->shop_description,
                        'shop_seo_keywords' => $request->shop_seo_keywords,
                        'shop_image_url' => 'https://cdn.mjegulla.com/store/images/'.$filename,
                    ]);
                } else {
                    $save->update([
                        'shop_name' => $request->shop_name,
                        'shop_description' => $request->shop_description,
                        'shop_seo_keywords' => $request->shop_seo_keywords,
                    ]);
                }

                if($save){
                    return redirect()->back()->with('success', trans('general.shop_updated_successfully'));
                }else{
                    return redirect()->back()->withErrors(trans('general.there_was_an_error'));
                }
            }else{
                abort(401, 'Unauthorized');
            }

        }else{
            abort(401, 'Unauthorized');
        }
    }




    public function statsView()
    {
        if(auth()->user()->managing_shop){
            $id = auth()->user()->managing_shop;
            $shop = Shop::find($id);
            if($shop->user_id === auth()->user()->id){
                return view('statsView');
            }else{
                abort(401, 'Unauthorized');
            }
        }
    }


    public function analitycs()
    {
        if(auth()->user()->managing_shop){
            $id = auth()->user()->managing_shop;
            $shop = Shop::find($id);
            if($shop->user_id === auth()->user()->id){

                $shopId = $shop->id;

                $all_visitors = StoreImpressions::where('store_id', $shop->id);
                $all_visitors_unique = StoreImpressions::where('store_id', $shop->id)->distinct('user_ip');


                $visitors_today = StoreImpressions::where('store_id', $shopId)
                    ->whereDate('created_at', today())
                    ->count();
                
                $visitors_this_week = StoreImpressions::where('store_id', $shopId)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count();
                
                $visitors_this_month = StoreImpressions::where('store_id', $shopId)
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->count();
                
                $visitors_today_unique = StoreImpressions::where('store_id', $shopId)
                    ->distinct('user_ip')
                    ->whereDate('created_at', today())
                    ->count();
                
                $visitors_this_week_unique = StoreImpressions::where('store_id', $shopId)
                    ->distinct('user_ip')
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count();
                
                $visitors_this_month_unique = StoreImpressions::where('store_id', $shopId)
                    ->distinct('user_ip')
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->count();
                


                $top_urls = StoreImpressions::where('store_id', $shop->id)
                ->select('url', \DB::raw('COUNT(*) as visitors'))
                ->groupBy('url')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(10)
                ->get();
            
            

                return view('analitycs',[
                    'shop' => $shop,
                    'all_visitors' => $all_visitors->count(),
                    'visitors_today' => $visitors_today,
                    'visitors_this_week' => $visitors_this_week,
                    'visitors_this_month' => $visitors_this_month,
                    
                    'all_visitors_unique' => $all_visitors_unique->count(),
                    'visitors_today_unique' => $visitors_today_unique,
                    'visitors_this_week_unique' => $visitors_this_week_unique,
                    'visitors_this_month_unique' => $visitors_this_month_unique,
                    
                    'top_urls' => $top_urls,
                ]);
            }else{
                abort(401, 'Unauthorized');
            }
        }
    }


    //variants
    public function variantView()
    {
        if(auth()->user()->managing_shop){
            $id = auth()->user()->managing_shop;
            $shop = Shop::find($id);
            if($shop->user_id === auth()->user()->id){
                $variants = Variants::where('shop_id', $shop->id)
                ->where('user_id', auth()->user()->id)
                ->with('product')
                ->where('deleted', 'no')->paginate(20);
                return view('variant_view', compact('variants'));
            }else{
                abort(401, 'Unauthorized');
            }
        }
    }
    


    //domains
    public function domainsView()
    {
        $id = auth()->user()->managing_shop;
        $shop = Shop::where('id', $id)->where('user_id', auth()->user()->id)->first();
        return view('domainsView',[
            'subdomain' => $shop->my_shop_url,
            'custom_domain' => ($shop->custom_domain) ? $shop->custom_domain : null,
            'active_custom_domain' => $shop->custom_domain_activated,
            'can_add_custom_domain' => (isset($shop->stripe_session)) ? 'yes' : 'no',
        ]);
    }


    public function updateSubdomain(Request $request)
    {
        $data = $request->validate([
            'my_shop_url' => 'required|string|min:4|max:100|regex:/^[a-zA-Z0-9\-]+$/|unique:shops,my_shop_url',
        ]);

        Shop::where('id', auth()->user()->managing_shop)
        ->where('user_id', auth()->user()->id)
        ->update(['my_shop_url' => $request->my_shop_url]);
    
        return back()->with('success', trans('general.subdomain_changed_success'));
    }


    public function addCustomDomain(Request $request)
    {
        $data = $request->validate([
            'custom_domain' => 'required|string|max:255|regex:/^(?!www\.)[A-Za-z0-9-]{1,63}\.[A-Za-z]{2,6}$/|unique:shops,custom_domain',
        ]);

        $can_add = Shop::where('id', auth()->user()->managing_shop)
        ->where('user_id', auth()->user()->id)
        ->whereNotNull('stripe_session')
        ->count();
    

        if($can_add){
            Shop::where('id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->update([
                'custom_domain' => $request->custom_domain,
                'custom_domain_activated' => 1,
            ]);
        }else{
            return back()->with('success', trans('general.only_paid_package_custom_domain'));    
        }

        $discord_event = [
            'shop_id' => auth()->user()->managing_shop,
            'custom_domain' => $request->custom_domain,
        ];
        event(new \App\Events\DiscordBot('custom-domain', $discord_event));
        return back()->with('success', trans('general.custom_domain_added_success'));

    }


    public function deleteCustomDomain($domain)
    {
        $shop = Shop::where('id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->where('custom_domain', $domain)
            ->update([
                'custom_domain' => '',
                'custom_domain_activated' => 0,
            ]);
        
        if ($shop) {
            $discord_event = [
                'shop_id' => auth()->user()->managing_shop,
                'custom_domain' => $domain,
            ];
            event(new \App\Events\DiscordBot('custom-domain-delete', $discord_event));
            return back()->with('success', trans('general.custom_domain_deleted_success'));
        } else {
            return back()->with('error', trans('general.there_was_an_error'));
        }
    }
    


    //check if shop exists
    public function checkName($my_shop_url)
    {
        $validator = Validator::make(['my_shop_url' => $my_shop_url], [
            'my_shop_url' => 'required|string|min:4|max:100|regex:/^[a-zA-Z0-9\-]+$/|unique:shops,my_shop_url',
        ]);
    
        if ($validator->fails()) {
            return response()->json(false);
        }
    
        return response()->json(true);
    }
    
    
    

    //shop plugins view
    public function pluginsView()
    {
        $id = auth()->user()->managing_shop;
        if(auth()->check()){
            //check if shop belongs to the user
            $shop = Shop::where([
                'id' => $id,
                'user_id' => auth()->user()->id
            ])->first();
            
            if($shop){
                //save for one month in cache
                //Cache::put('shop_id', $shop->id, 2592000);
                $purchase_plugins = Plugins::whereNotIn('id', function($query) use ($shop) {
                    $query->select('plugin_id')
                          ->from('purchased_plugins')
                          ->where('user_id', auth()->user()->id)
                          ->where('shop_id', $shop->id);
                })
                ->orderByDesc('id')
                ->get();


                $my_plugins = PurchasedPlugins::where([
                    'user_id' => auth()->user()->id,
                    'shop_id' => $shop->id,
                ])->get();
    
                return view('plugins', [
                    'id' => $id,
                    'shop_id' => $shop->id,
                    'name' => $shop->shop_name,
                    'image_url' => $shop->shop_image_url,
                    'purchase_plugins' => $purchase_plugins,
                    'my_plugins' => $my_plugins,
                ]);
            }else{
                abort(401, 'Unauthorized');    
            }
        }else{
            abort(401, 'Unauthorized');
        }  
    }
    

    //activate plugin
    public function activatePlugin($id)
    {
        if(auth()->user()->managing_shop){

            $my_plugins = PurchasedPlugins::where([
                'user_id' => auth()->user()->id,
                'shop_id' => auth()->user()->managing_shop,
                'id' => $id,
            ])->first();
            if($my_plugins){
                $my_plugins->enabled = 'yes';
                $save = $my_plugins->save();
                if($save){
                    return redirect()->back()->with('success', trans('general.plugin_activated_successfully'));
                }else{
                    return redirect()->back()->withErrors(trans('general.there_was_an_error'));
                }
            }else{
                $purchased_plugin = Plugins::findOrFail($id);
                $purchase = new PurchasedPlugins();
                $purchase->user_id = auth()->user()->id;
                $purchase->shop_id = auth()->user()->managing_shop;
                $purchase->name = $purchased_plugin->name;
                $purchase->image = $purchased_plugin->image;
                $purchase->description = $purchased_plugin->description;
                $purchase->plugin_id = $purchased_plugin->id;
                $purchase->enabled = 'yes';
                $save = $purchase->save();
                if($save){
                    return redirect()->back()->with('success', trans('general.plugin_activated_successfully'));
                }else{
                    return redirect()->back()->withErrors(trans('general.there_was_an_error'));
                }
            }

        }
    }
    
    

    public function deactivatePlugin($id)
    {
        if(auth()->user()->managing_shop){

            $my_plugins = PurchasedPlugins::where([
                'user_id' => auth()->user()->id,
                'shop_id' => auth()->user()->managing_shop,
                'id' => $id,
            ])->first();

            if($my_plugins){
                $my_plugins->enabled = 'no';
                $save = $my_plugins->save();
                if($save){
                    return redirect()->back()->with('success', trans('general.plugin_deactivated_successfully'));
                }else{
                    return redirect()->back()->withErrors(trans('general.there_was_an_error'));
                }
            }
            return redirect()->back()->withErrors(trans('general.there_was_an_error'));
        }
        return redirect()->back()->withErrors(trans('general.there_was_an_error'));
    }




    //shop themes view
    public function themesView()
    {
        $id = auth()->user()->managing_shop;
        $active_theme = Shop::where('id', $id)->value('theme');
        return view('themes',[
            'active_theme' => $active_theme,
        ]);
    }


    // store change theme
    public function changeTheme($theme_id)
    {
        $user = auth()->user();
    
        if ($user) {
            $store_id = $user->managing_shop;

            $store = Shop::find($store_id);
    
            if ($store) {
                // Check if the provided theme_id is either 1 or 2
                if ($theme_id == 1 || $theme_id == 2) {
                    // Assign the theme to the store
                    $store->theme = $theme_id;
                    $store->save();
    
                    return redirect()->back()->with('success', trans('general.theme_changed_successfully'));
                } else {
                    return redirect()->back()->withErrors(trans('general.theme_invalid'));
                }
            } else {
                return redirect()->back()->withErrors(trans('general.store_not_found'));
            }
        }
    
        abort(403, 'Unauthorized');
    }
    



    //shop orders view
    public function ordersView()
    {
        if(auth()->user()->managing_shop){
            $shop = Shop::findOrFail(auth()->user()->managing_shop);

            if($shop->user_id === auth()->user()->id){
                $orders = Orders::where(['shop_id' => $shop->id, 'finished' => 'yes'])->orderBy('id', 'DESC')->paginate(20);



                $all_orders = [];
                foreach ($orders as $order) {
                    $order_id = $order->id;
                    $products = json_decode($order->product_id, true);
                    $variants = json_decode($order->variant_id, true);
                    $shop_id = $order->shop_id;
                    $amount = $order->amount;
                    $shipping_price = $order->shipping_price;
                    $payment_method = $order->payment_method;
                    $cj_status = $order->cj_status;
                    $cj_postal = $order->cj_postal;
                    $cj_paid = $order->cj_paid;
                    $cj_tracking = $order->cj_tracking;
                    $created_at = $order->created_at;
                
                    $order_data = [
                        'order_id' => $order_id,
                        'shipping_price' => $shipping_price,
                        'products' => []
                    ];
                
                    //get from products table
                    foreach ($products as $product) {
                        $prod_model = Products::where([
                            'id' => $product,
                            'shop_id' => $shop_id,
                        ])->first();
                
                        if ($prod_model) {
                            $product_data = [
                                'product_name' => $prod_model->product_name,
                                'product_url' => $prod_model->product_url,
                                'product_single_image' => $prod_model->product_single_image,
                                'price' => $prod_model->base_price_discount ? $prod_model->base_price - ($prod_model->base_price * $prod_model->base_price_discount / 100) : $prod_model->base_price,
                                'payment_method' => $payment_method,
                                'cj_status' => $cj_status,
                                'cj_postal' => $cj_postal,
                                'cj_paid' => $cj_paid,
                                'cj_tracking' => $cj_tracking,
                                'created_at' => $order->created_at,
                            ];
                
                            $order_data['products'][] = $product_data;
                        }
                    }
                
                    //get from variants combined with products table to get other details.
                    foreach ($variants as $variant) {
                        $var_model = Variants::where([
                            'variant_id' => $variant,
                            'shop_id' => $shop_id,
                        ])->first();
                
                        //get product ids and fetch other data from product model
                        if ($var_model) {
                            $prod_model = Products::where([
                                'id' => $var_model->product_id,
                                'shop_id' => $var_model->shop_id,
                            ])->first();
                
                            if ($prod_model) {
                                $product_data = [
                                    'product_name' => $prod_model->product_name,
                                    'product_url' => $prod_model->product_url,
                                    'product_single_image' => $prod_model->product_single_image,
                                    'price' => $var_model->price,
                                    'payment_method' => $payment_method,
                                    'cj_status' => $cj_status,
                                    'cj_postal' => $cj_postal,
                                    'cj_paid' => $cj_paid,
                                    'cj_tracking' => $cj_tracking,
                                    'created_at' => $order->created_at,
                                ];
                
                                $order_data['products'][] = $product_data;
                            }
                        }
                    }
                
                    $all_orders[$order_id] = $order_data;
                }


                return view('orders',[
                    'orders' => $all_orders,
                    'pagination' => $orders->links(),
                ]);
            }else{
                abort(401, 'Unauthenticated');
            }
        }
    }




    public function singleOrderView($id)
    {

        $order = Orders::findOrFail($id);
        $shop = Shop::where(['id' => $order->shop_id, 'user_id' => auth()->user()->id])->first();
        if($shop->user_id === auth()->user()->id){
            $orders = $order->where('id', $id)->get();


            $all_orders = [];
            foreach ($orders as $order) {
                $order_id = $order->id;
                $products = json_decode($order->product_id, true);
                $variants = json_decode($order->variant_id, true);
                $shop_id = $order->shop_id;
                $amount = $order->amount;
                $shipping_price = $order->shipping_price;
                $shipped = $order->shipped;
                $delivered = $order->delivered;
                $payment_method = $order->payment_method;
                $payment_completed = $order->completed;
                $created_at = $order->created_at;
            
                $order_data = [
                    'order_id' => $order_id,
                    'shipping_price' => $shipping_price,
                    'products' => [],
                    'billing' => [],
                ];
            
                //get from products table
                foreach ($products as $product) {
                    $prod_model = Products::where([
                        'id' => $product,
                        'shop_id' => $shop_id,
                    ])->first();
            
                    if ($prod_model) {
                        $product_data = [
                            'product_id' => $prod_model->id,
                            'product_name' => $prod_model->product_name,
                            'product_url' => $prod_model->product_url,
                            'product_single_image' => $prod_model->product_single_image,
                            'price' => $prod_model->base_price_discount ? $prod_model->base_price - ($prod_model->base_price * $prod_model->base_price_discount / 100) : $prod_model->base_price,
                            'shipped' => $shipped,
                            'delivered' => $delivered,
                            'payment_method' => $payment_method,
                            'payment_completed' => $payment_completed,
                            'created_at' => $order->created_at,
                        ];
            
                        $order_data['products'][] = $product_data;
                    }
                }


                //get customer address.
                $billing = CustomerBilling::where('id', $order->billing_id)->get();
                if($billing){
                    foreach($billing as $b){
                        $billing_data = [
                            'user_id' => $b->user_id,
                            'shop_id' => $b->shop_id,
                            'first_name' => $b->first_name,
                            'last_name' => $b->last_name,
                            'company' => $b->company,
                            'country' => ShippingRates::where('id', $b->country)->value('country'),
                            'address' => $b->address,
                            'city' => $b->city,
                            'zip' => $b->zip,
                            'phone' => $b->phone,
                            'order_note' => $b->order_note,
                            'payment_method' => $b->payment_method,
                            'created_at' => $b->created_at,
                            'updated_at' => $b->updated_at,
                        ];
                    }
                    $order_data['billing'] = $billing_data;
                }
            
                //get from variants combined with products table to get other details.
                foreach ($variants as $variant) {
                    $var_model = Variants::where([
                        'variant_id' => $variant,
                        'shop_id' => $shop_id,
                    ])->first();
            
                    //get product ids and fetch other data from product model
                    if ($var_model) {
                        $prod_model = Products::where([
                            'id' => $var_model->product_id,
                            'shop_id' => $var_model->shop_id,
                        ])->first();
            
                        if ($prod_model) {
                            $product_data = [
                                'product_id' => $prod_model->id,
                                'product_name' => $prod_model->product_name,
                                'product_url' => $prod_model->product_url,
                                'product_sku' => $prod_model->product_sku,
                                'variant' => [
                                    'id' => $var_model->variant_id,
                                    'size' => $var_model->size,
                                    'color' => $var_model->color,
                                    'material' => $var_model->material,
                                ],
                                'product_single_image' => $prod_model->product_single_image,
                                'price' => $var_model->price,
                                'shipped' => $shipped,
                                'delivered' => $delivered,
                                'payment_method' => $payment_method,
                                'payment_completed' => $payment_completed,
                                'created_at' => $order->created_at,
                            ];
            
                            $order_data['products'][] = $product_data;
                        }
                    }
                }
            
                $all_orders = $order_data;
            }


            return view('order', [
                'order' => $all_orders,
            ]);
        }else{
            abort(401, 'Unauthenticated');
        }

    }





    public function singleOrderInvoice($id)
    {

        $order = Orders::findOrFail($id);
        $shop = Shop::where(['id' => $order->shop_id, 'user_id' => auth()->user()->id])->first();

        if(!$shop){
            abort(403);
        }

        if($shop->user_id === auth()->user()->id){
            $orders = $order->where('id', $id)->get();


            $all_orders = [];
            foreach ($orders as $order) {
                $order_id = $order->id;
                $products = json_decode($order->product_id, true);
                $variants = json_decode($order->variant_id, true);
                $shop_id = $order->shop_id;
                $amount = $order->amount;
                $shipping_price = $order->shipping_price;
                $shipped = $order->shipped;
                $delivered = $order->delivered;
                $payment_method = $order->payment_method;
                $payment_completed = $order->completed;
                $created_at = $order->created_at;
            
                $order_data = [
                    'order_id' => $order_id,
                    'shipping_price' => $shipping_price,
                    'order_price' => $amount,
                    'shop_icon' => $shop->shop_image_url,
                    'shop_name' => $shop->shop_name,
                    'payment_method' => $payment_method,
                    'created_at' => $created_at,
                    'products' => [],
                    'billing' => [],
                    'shop_details' => $shop,
                ];
            
                //get from products table
                foreach ($products as $product) {
                    $prod_model = Products::where([
                        'id' => $product,
                        'shop_id' => $shop_id,
                    ])->first();
            
                    if ($prod_model) {
                        $product_data = [
                            'product_name' => $prod_model->product_name,
                            'product_url' => $prod_model->product_url,
                            'product_single_image' => $prod_model->product_single_image,
                            'price' => $prod_model->base_price_discount ? $prod_model->base_price - ($prod_model->base_price * $prod_model->base_price_discount / 100) : $prod_model->base_price,
                            'shipped' => $shipped,
                            'delivered' => $delivered,
                            'payment_method' => $payment_method,
                            'payment_completed' => $payment_completed,
                            'created_at' => $order->created_at,
                        ];
            
                        $order_data['products'][] = $product_data;
                    }
                }


                //get customer address.
                $billing = CustomerBilling::where('id', $order->billing_id)->get();
                if($billing){
                    foreach($billing as $b){
                        $billing_data = [
                            'user_id' => $b->user_id,
                            'shop_id' => $b->shop_id,
                            'first_name' => $b->first_name,
                            'last_name' => $b->last_name,
                            'company' => $b->company,
                            'country' => ShippingRates::where('id', $b->country)->value('country'),
                            'address' => $b->address,
                            'city' => $b->city,
                            'zip' => $b->zip,
                            'phone' => $b->phone,
                            'order_note' => $b->order_note,
                            'payment_method' => $b->payment_method,
                            'created_at' => $b->created_at,
                            'updated_at' => $b->updated_at,
                        ];
                    }
                    $order_data['billing'] = $billing_data;
                }
            
                //get from variants combined with products table to get other details.
                foreach ($variants as $variant) {
                    $var_model = Variants::where([
                        'variant_id' => $variant,
                        'shop_id' => $shop_id,
                    ])->first();
            
                    //get product ids and fetch other data from product model
                    if ($var_model) {
                        $prod_model = Products::where([
                            'id' => $var_model->product_id,
                            'shop_id' => $var_model->shop_id,
                        ])->first();
            
                        if ($prod_model) {
                            $product_data = [
                                'product_name' => $prod_model->product_name,
                                'product_url' => $prod_model->product_url,
                                'product_sku' => $prod_model->product_sku,
                                'variant' => [
                                    'id' => $var_model->variant_id,
                                    'size' => $var_model->size,
                                    'color' => $var_model->color,
                                    'material' => $var_model->material,
                                ],
                                'product_single_image' => $prod_model->product_single_image,
                                'price' => $var_model->price,
                                'shipped' => $shipped,
                                'delivered' => $delivered,
                                'payment_method' => $payment_method,
                                'payment_completed' => $payment_completed,
                                'created_at' => $order->created_at,
                            ];
            
                            $order_data['products'][] = $product_data;
                        }
                    }
                }
            
                $all_orders = $order_data;
            }


            return view('invoice', [
                'invoice' => $all_orders,
            ]);
        }else{
            abort(401, 'Unauthenticated');
        }

    }



    //shop payment methods
    public function ShopPaymentMethodView()
    {
        $id = auth()->user()->managing_shop;
        $shop = Shop::findOrFail($id);
        if($shop->user_id === auth()->user()->id){
            return view('shop_payment_methods',[
                'payment_methods' => json_decode($shop->payment_methods),
                'shop' => $shop,
            ]);
        }
    }

    //update payment method
    public function updateShopPaymentMethods($id, $method)
    {
        $shop = Shop::findOrFail($id);
        if ($shop->user_id === auth()->user()->id) {
            $payment_methods = json_decode($shop->payment_methods);
            if ($method == 'credit_card' && $shop->store_type == 'dropshipping') {
                $payment_methods->credit_card = !$payment_methods->credit_card;
            } else {
                if($shop->store_type == 'local_store'){
                    $payment_methods->cash = !$payment_methods->cash;
                }else{
                    // return redirect()->back()->withErrors(trans('general.upgrade_package_to_take_cash_payments'));
                    return redirect()->back()->withErrors(trans('general.there_was_an_error'));
                }
            }
            $shop->payment_methods = json_encode($payment_methods);
            $save = $shop->save();
            if($save){
                return redirect()->back()->with('success', trans('general.payment_methods_update_success'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }
        }else{
            abort('401', 'Unauthorized');
        }
    }
    


    //shop package upgdade view
    public function ShopPackageUpgradeView(Request $request){
        $id = auth()->user()->managing_shop;
        $shop = Shop::findOrFail($id);
        if ($shop->user_id === auth()->user()->id) {
            if(request()->has('session_id')){
                (new \App\Http\Controllers\Subscriptions)->upgradeHandle(request()->input('session_id'));
            }

            $package = SubscriptionPackages::where('shop_id', $id)->first();
            if($package){
                $has_active = true;
            }else{
                $has_active = false;
            }
            return view('shop_package_upgrade',[
                'has_active' => $has_active,
                'package' => $package,
            ]);
        }else{
            abort('401', 'Unauthorized');
        }
    }


    public function customCSS(Request $request)
    {
        $package = SubscriptionPackages::where('shop_id', auth()->user()->managing_shop)->first();
        if($package){
            $has_active = true;
        }else{
            $has_active = false;
        }
        $custom_css = Shop::where('id', auth()->user()->managing_shop)->where('user_id', auth()->user()->id)->value('custom_css');
        return view('custom_css',[
            'custom_css' => $custom_css,
            'has_active_package' => $has_active,
        ]);
    }


    public function customCSSUpdate(Request $request)
    {
        $css = $request->custom_css;
        $data = $request->validate([
            'custom_css' => 'string|nullable',
        ]);
        Shop::where('id', auth()->user()->managing_shop)->where('user_id', auth()->user()->id)->update([
            'custom_css' => $css,
        ]);

        return redirect()->back()->with('success', trans('general.custom_css_updated'));
    }



    public function ShopChangeTypeView(Request $request)
    {
        return view('change_store_type');
    }


    public function ShopChangeType(Request $request)
    {
        $data = $request->validate([
            'store_type' => 'required|in:dropshipping,local_store',
        ]);
        $my_store = Shop::where('id', auth()->user()->managing_shop)->first();
        $store_type = $request->store_type;
        if(auth()->user()->unshippedOrdersCount()){
            return back()->with('error', trans('general.change_store_error_pending_orders'));
        }else{

            if($my_store->store_type !== $store_type){

                //delete store products, variants, product images...
                Products::where('shop_id', $my_store->id)->update(['deleted' => 'yes']);
                ProductImages::where('shop_id', $my_store->id)->update(['deleted' => 'yes']);
                Variants::where('shop_id', $my_store->id)->update(['deleted' => 'yes']);
                Cart::where('shop_id', $my_store->id)->delete();
                Wishlist::where('shop_id', $my_store->id)->delete();
                ShippingRates::where('shop_id', $my_store->id)->delete();
                Categories::where('shop_id', $my_store->id)->delete();


                //check if subscription active, to reset the total of products available.
                $sub_pack = SubscriptionPackages::where('shop_id', $my_store->id)->first();
                if($sub_pack){
                    if($sub_pack->package_type == 'Basic'){
                        $products_available = 50;
                    }
                    if($sub_pack->package_type == 'Premium'){
                        $products_available = 'unlimited';
                    }
                }else{
                    $products_available = 2;
                }
                $my_store->products_available = $products_available;
                $my_store->total_products = 0;



                if($store_type == 'dropshipping'){
                    $my_store->store_type = 'dropshipping';
                    $shippingRates = (new ShippingRatesController)->AutoCreate($my_store->id);
                    $shop_payment_methods = [
                        'credit_card' => true,
                        'cash' => false,
                    ];
                    $json_payment_methods = json_encode($shop_payment_methods);
                    $my_store->payment_methods = $json_payment_methods;
                }else{
                    $my_store->store_type = 'local_store';
                    $shippingRates = (new ShippingRatesController)->LocalSelling($my_store->id);
                    $shop_payment_methods = [
                        'credit_card' => false,
                        'cash' => true,
                    ];
                    $json_payment_methods = json_encode($shop_payment_methods);
                    $my_store->payment_methods = $json_payment_methods;
                }
                $my_store->save();
                return back()->with('success', trans('general.change_store_type_success'));
            }else{
                return back()->with('error', trans('general.change_store_type_same'));
            }
        }
        
    }



    public function updateGoogleAnalytics(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|min:12|max:12',
        ]);
    
        $shop = Shop::where('id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->first();
    
        if ($shop) {
            $update = $shop->update([
                'google_analytics' => $request->code,
            ]);
    
            if ($update) {
                return back()->with('success', trans('general.google_analytics_success'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }
        }
    
        return redirect()->back()->withErrors(trans('general.there_was_an_error'));
    }
    

    public function updateFacebookPixel(Request $request){
        $data = $request->validate([
            'pixel_code' => 'required|string|min:16|max:16',
        ]);
    
        $shop = Shop::where('id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->first();
    
        if ($shop) {
            $update = $shop->update([
                'facebook_pixel' => $request->pixel_code,
            ]);
    
            if ($update) {
                return back()->with('success', trans('general.facebook_pixel_success'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }
        }
    
        return redirect()->back()->withErrors(trans('general.there_was_an_error'));
    }



    public function updateTikTokPixel(Request $request)
    {
        $data = $request->validate([
            'pixel_code' => 'required|string|min:13|max:13',
        ]);
    
        $shop = Shop::where('id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->first();
    
        if ($shop) {
            $update = $shop->update([
                'tiktok_pixel' => $request->pixel_code,
            ]);
    
            if ($update) {
                return back()->with('success', trans('general.tiktok_pixel_success'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }
        }
    
        return redirect()->back()->withErrors(trans('general.there_was_an_error'));
    }


}
