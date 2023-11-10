<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Variants;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Shop;
use App\Models\Products;
use App\Models\PaymentProviders;
use App\Models\Customer;
use App\Models\Categories;
use App\Models\PurchasedPlugins;
use App\Models\ShippingRates;
use App\Models\CustomerBilling;
use App\Models\Orders;
use Stripe\Stripe;
use Stripe\Checkout\Session;

use App\Models\User;
use App\Notifications\OrderReceive;
use App\Models\CjDropshipping;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $customer_id = auth()->guard('customers')->user()->id;
        $size = $request->size;
        $color = $request->color;
        $material = $request->material;
        $productId = $request->product_id;
        $shopId = $request->shop_id;

        $variant_id = Variants::where('product_id', $productId)
        ->where('shop_id', $shopId);

        if($request->has('size') && $size !== "undefined"){
            $variant_id = $variant_id->where('size', $size);
        }else{
            $size = '';
        }
        if($request->has('color') && $color !== "undefined"){
            $variant_id = $variant_id->where('color', $color);
        }else{
            $color = '';
        }
        if($request->has('material') && $material !== "undefined"){
            $variant_id = $variant_id->where('material', $material);
        }else{
            $material = '';
        }

        $variant_id = $variant_id->pluck('variant_id');

        if($variant_id->isEmpty()){
            $variant_id = '';
        }else{
            $variant_id = $variant_id->first();
        }

        $sent_to_cart = Cart::firstOrCreate([
            'user_id' => $customer_id,
            'shop_id' => $shopId,
            'product_id' => $productId,
            'variant_id' => $variant_id,
        ]);

        if ($sent_to_cart->wasRecentlyCreated) {
            // cart record was just created
            return response()->json([
                'message' => trans('general.product_added_to_cart'),
                'added' => true,
        ]);
        } else {
            $sent_to_cart->delete();
            return response()->json([
                'message' => trans('general.product_removed_from_cart'),
                'deleted' => true,
            ]);
        }


    }




    public function myCartList(Request $request, $subdomain = null)
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

        $cart = Cart::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->get();
        

        //random 4 products for related products
        $related_products = Products::inRandomOrder()
            ->where('shop_id', $shop->id)
            ->where('deleted', 'no')
            ->take(4)
            ->get();

        return view('shops.theme_'.$shop->theme.'.cart',[
            'shop' => $shop,
            'categories' => $categories,
            'header_message' => $shop->header_message,
            'social_networks' => json_decode($shop->social_networks, true),
            'social_switch' => $shop->social_networks,
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'wishlist' => $wishlist,
            'cart_count' => $cart_count,
            'cart' => $cart,
            'related_products' => $related_products,
        ]);
    }



    public function myOrders(Request $request, $subdomain = null)
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

        $cart_count = Cart::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->count();

        $orders = Orders::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
            // 'completed' => 'yes',
        ])->orderBy('id', 'DESC')->get();
        
        
        $all_orders = [];
        foreach ($orders as $order) {
            $order_id = $order->id;
            $products = json_decode($order->product_id, true);
            $variants = json_decode($order->variant_id, true);
            $shop_id = $order->shop_id;
            $amount = $order->amount;
            $shipping_price = $order->shipping_price;
            $shipped = $order->shipped;
            $payment_method = $order->payment_method;
        
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
                        'shipped' => $shipped,
                        'payment_method' => $payment_method,
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
                            'shipped' => $shipped,
                            'payment_method' => $payment_method,
                        ];
        
                        $order_data['products'][] = $product_data;
                    }
                }
            }
        
            $all_orders[$order_id] = $order_data;
        }
        
        

        //random 4 products for related products
        $related_products = Products::inRandomOrder()
            ->where('shop_id', $shop->id)
            ->where('deleted', 'no')
            ->take(4)
            ->get();

        return view('shops.theme_'.$shop->theme.'.orders',[
            'shop' => $shop,
            'categories' => $categories,
            'header_message' => $shop->header_message,
            'social_networks' => json_decode($shop->social_networks, true),
            'social_switch' => $shop->social_networks,
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'cart_count' => $cart_count,
            'related_products' => $related_products,
            'orders' => $all_orders,
        ]);
    }


    public function myCheckout(Request $request, $subdomain = null)
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

        $shipping_rates = ShippingRates::where([
            'shop_id' => $shop->id,
            'enabled' => 'yes',
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

        $cart = Cart::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->get();
        
        $customer_billing = CustomerBilling::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->first() ?? new CustomerBilling();
        

        //calculate CjDropshipping shipping options and fees
        $token = CjDropshipping::where('shop_id', $shop->id)->where('user_id', $shop->user_id)->value('generated_token');
        $cj_prod_variants = [];
        foreach ($cart as $crt) {
            $cj_prod_variants[] = [
                'quantity' => 1,
                'vid' => $crt->variant_id
            ];
        }
        $shipping_to = ShippingRates::where('id', $customer_billing->country)->first();
        if($shipping_to){
            $cj_cart_data = [
                'startCountryCode' => 'CN',
                'endCountryCode' => strtoupper($shipping_to->country_code),
                'products' => $cj_prod_variants,
            ];
            $shipping_fees = (new CjDropshippingController)->shipping_calculator($token, $cj_cart_data);
        }else{
            $shipping_fees = 0;
        }
        

        //random 4 products for related products
        $related_products = Products::inRandomOrder()
            ->where('shop_id', $shop->id)
            ->where('deleted', 'no')
            ->take(4)
            ->get();

        return view('shops.theme_'.$shop->theme.'.checkout',[
            'shop' => $shop,
            'categories' => $categories,
            'header_message' => $shop->header_message,
            'social_networks' => json_decode($shop->social_networks, true),
            'social_switch' => $shop->social_networks,
            'plugins' => $plugins,
            'wishlist_count' => $wishlist_count,
            'wishlist' => $wishlist,
            'cart_count' => $cart_count,
            'cart' => $cart,
            'related_products' => $related_products,
            'shipping_rates' => $shipping_rates,
            'customer_billing' => $customer_billing,
            'cj_shipping_fees' => $shipping_fees,
        ]);
    }





    public function checkout(Request $request, $subdomain = null)
    {
        $host = $request->getHost();
        $custom_domain = Shop::where('custom_domain', $host)->first();
        if ($custom_domain) {
            $subdomain = $custom_domain->my_shop_url;
        }


        $shop = Shop::where('my_shop_url', $subdomain)->firstOrFail();


        $data = $request->validate([
            'first_name' => 'required|string|min:1|max:30',
            'last_name' => 'required|string|min:1|max:30',
            'company' => 'nullable|string|min:1|max:30',
            'country' => 'required|numeric',
            'address' => 'required|string|min:5|max:200',
            'city' => 'required|string|min:3|max:20',
            'zip' => 'required|string|min:1|max:20',
            'phone' => 'required|string|min:8|max:20',
            'order_note' => 'nullable|string|max:200',
            'payment_method' => 'required',
            'shipping_company' => ($shop->store_type == 'dropshipping') ? 'required' : 'nullable',
        ]);        
        
        
        // Sanitize input
        $data = array_map(function($value) {
            return trim(htmlspecialchars($value));
        }, $data);

        //add user_id and shop_id to data array
        $data = array_merge($data, [
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ]);
        
        $updateBilling = (new CustomerBillingController)->update($data);

        
        $order = Cart::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->get();
        
        $with_variant = [];
        $without_variant = [];
        

        // split cart in items that have variant and dont.
        foreach ($order as $item) {
            if ($item->variant_id) {
                $with_variant[] = $item;
            } else {
                $without_variant[] = $item;
            }
        }


        $order_total_amount = 0;
        //sum the amount on cart that has variant ids.
        foreach ($with_variant as $with_variant) {
            $v = Variants::where([
                'variant_id' => $with_variant->variant_id,
                'shop_id' => $shop->id,
            ])->first();
            $order_total_amount += $v->price;
        }
        
        //sum the amount on cart that has dont variant ids.
        foreach ($without_variant as $without_variant) {
            $p = Products::where([
                'id' => $without_variant->product_id,
                'shop_id' => $shop->id,
                ])->first();
            if($p->base_price_discount){
                $order_total_amount += $p->base_price - ($p->base_price * $p->base_price_discount / 100);
            }else{
                $order_total_amount += $p->base_price;
            }
        }
        

        if($shop->store_type == 'dropshipping'){
            $shipping_price = $this->shippingCountryChange($request, $shop->id);
        
            $cj_shipping_price = 0;
            foreach($shipping_price as $sh_p){
                if($sh_p['logisticName'] === $request->shipping_company){
                    //add shipping price to total amount
                    $order_total_amount += $sh_p['logisticPrice'];
                    $cj_shipping_price = $sh_p['logisticPrice'];
                    $cj_postal = $sh_p['logisticName'];
                }
            }
        }else{
            //GET SHIPPING PRICE
            $shipping = ShippingRates::where([
                'id' => $request->country,
                'shop_id' => $shop->id,
                'enabled' => 'yes',
            ])->first();


            //add shipping price to total amount
            $order_total_amount += $shipping->price;
        }

        

        //get product names to add as item for stripe description
        $stripe_description = '';
        foreach($order as $order){
            $prod = Products::where('id', $order->product_id)->first();
            $stripe_description .= $prod->product_name."\n";
        }


        //get product_ids and add into a json object
        $product_id_array = [];
        $variant_id_array = [];
        $cart_details = Cart::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->get();
        foreach ($cart_details as $cart) {
            if ($cart['variant_id']) {
                $variant_id_array[] = $cart['variant_id'];
            } else {
                $product_id_array[] = $cart['product_id'];
            }
        }        
        $product_id_json = json_encode($product_id_array);
        $variant_id_json = json_encode($variant_id_array);

        
        if($request->payment_method == 'credit_card'){
            (new CustomerBillingController)->payViaStripe($order_total_amount,$cj_shipping_price,$stripe_description,$shop->id,$updateBilling->id,$product_id_json,$variant_id_json,$cj_postal);
        }else{
            (new CustomerBillingController)->payViaCash($order_total_amount,$shipping->price,$stripe_description,$shop->id,$updateBilling->id,$product_id_json,$variant_id_json);
        }
    
    }


    //shipping country change, calculate fees and shipping companies
    public function shippingCountryChange(Request $request, $shop_id = null)
    {
        $country_id = $request->country_id ?? $request->country;
        $shop_id = $request->shop_id ?? $shop_id;


        $shop = Shop::find($shop_id);

        $cart = Cart::where([
            'user_id' => auth()->guard('customers')->user()->id,
            'shop_id' => $shop->id,
        ])->get();


        //calculate CjDropshipping shipping options and fees
        $token = CjDropshipping::where('shop_id', $shop->id)->where('user_id', $shop->user_id)->value('generated_token');
        $cj_prod_variants = [];
        foreach ($cart as $crt) {
            $cj_prod_variants[] = [
                'quantity' => 1,
                'vid' => $crt->variant_id
            ];
        }
        $shipping_to = ShippingRates::where('id', $country_id)->first();
        if($shipping_to){
            $cj_cart_data = [
                'startCountryCode' => 'CN',
                'endCountryCode' => strtoupper($shipping_to->country_code),
                'products' => $cj_prod_variants,
            ];
            $shipping_fees = (new CjDropshippingController)->shipping_calculator($token, $cj_cart_data);
            $shipping_fees = $shipping_fees['data'];
        }else{
            $shipping_fees = 0;
        }

        return $shipping_fees;

    }


        //order complete callback
        public function orderCompleted(Request $request)
        {
            // Check if a Stripe Checkout session ID was provided
            if ($request->has('session_id') && (strlen($request->input('session_id')) > 60)) {
                //set stripe api key.
                Stripe::setApiKey(PaymentProviders::select('private_key')->where('name', 'Stripe')->value('private_key'));
                // Retrieve the Stripe Checkout session ID from the request
                $sessionId = $request->input('session_id');
                // Retrieve the Stripe Checkout session using the ID
                try {
                    $session = Session::retrieve($sessionId);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    if ($e->getHttpStatus() === 404) {
                        return response()->json([
                            'message' => trans('general.payment_does_not_exist'),
                        ]);
                    } else {
                        // handle other types of exceptions as needed
                    }
                }
                                
    
                // Check the payment status of the session
                if ($session->payment_status === 'paid') {
                    $metadata = $session->metadata;
                    $order_id = $metadata->order_id;


                    $shop = Shop::where('id', $metadata->shop_id)->firstOrFail();
                    $categories = \App\Models\MenuItem::where('shop_id', $shop->id)->get();
                    $plugins = PurchasedPlugins::where([
                        'shop_id' => $shop->id,
                        'user_id' => $shop->user_id,
                    ])->get();
            
                    $shipping_rates = ShippingRates::where([
                        'shop_id' => $shop->id,
                        'enabled' => 'yes',
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
            
                    $cart = Cart::where([
                        'user_id' => auth()->guard('customers')->user()->id,
                        'shop_id' => $shop->id,
                    ])->get();

                    //random 4 products for related products
                    $related_products = Products::inRandomOrder()
                    ->where('shop_id', $shop->id)
                    ->where('deleted', 'no')
                    ->take(4)
                    ->get();

                    $order = Orders::where('id', $order_id)->first();
    

                    
                    //if order exists
                    if($order){
                        //decrement quantity left of every variant that was in the order, and add a sale to the product.
                        foreach ($cart as $item) {
                            $v_id = $item->variant_id;
                            $var = Variants::where('variant_id', $v_id)->first(); 
                            $prod = Products::where('id', $item->product_id)->first();

                            if ($var) { 
                                $var->decrement('quantity_left', 1); 
                            }
                            if($prod){
                                $prod->increment('product_sales', 1);
                                $prod->decrement('quantity', 1);
                            }
                        }                        
                        //delete cart products, after completed order.
                        foreach ($cart as $cart) {
                            $cart->delete();
                        }
                        $order->completed = 'yes';

                        $order->payment_method = 'credit_card';
                        $order->save();

                        if($order->finished == 'no'){
                            //add balance to shop.
                            $this->add_balance_to($order->shop_id, $order->amount, 'credit_card');

                            //sent notification to shop owner
                            $this->sendNotification($shop->user_id, $order->shop_id, '1', $order->amount);

                            //sent notification via email
                            $user = User::where('id', $shop->user_id)->first();
                            $order_url = 'https://mjegulla.com/order/'.$order->id;
                        }

                        //added to check if we should increment the balance on user or not
                        $order->finished = 'yes';
                        $order->save();

                        return view('shops.theme_'.$shop->theme.'.order_completed',[
                            'shop' => $shop,
                            'categories' => $categories,
                            'header_message' => $shop->header_message,
                            'social_networks' => json_decode($shop->social_networks, true),
                            'social_switch' => $shop->social_networks,
                            'plugins' => $plugins,
                            'wishlist_count' => $wishlist_count,
                            'wishlist' => $wishlist,
                            'cart_count' => $cart_count,
                            'cart' => $cart,
                            'related_products' => $related_products,
                            'shipping_rates' => $shipping_rates,
                        ])->with('success', true);
                    }else{
                        return view('shops.theme_'.$shop->theme.'.order_completed',[
                            'shop' => $shop,
                            'categories' => $categories,
                            'header_message' => $shop->header_message,
                            'social_networks' => json_decode($shop->social_networks, true),
                            'social_switch' => $shop->social_networks,
                            'plugins' => $plugins,
                            'wishlist_count' => $wishlist_count,
                            'wishlist' => $wishlist,
                            'cart_count' => $cart_count,
                            'cart' => $cart,
                            'related_products' => $related_products,
                            'shipping_rates' => $shipping_rates,
                        ])->with('error', true);
                    }
                }else{
                    return view('shops.theme_'.$shop->theme.'.order_completed',[
                        'shop' => $shop,
                        'categories' => $categories,
                        'header_message' => $shop->header_message,
                        'social_networks' => json_decode($shop->social_networks, true),
                        'social_switch' => $shop->social_networks,
                        'plugins' => $plugins,
                        'wishlist_count' => $wishlist_count,
                        'wishlist' => $wishlist,
                        'cart_count' => $cart_count,
                        'cart' => $cart,
                        'related_products' => $related_products,
                        'shipping_rates' => $shipping_rates,
                    ])->with('error', true);
                }
            }else{
                //cash payment
                $payment_id = $request->input('session_id');

                $order = Orders::where('stripe_payment_id', $payment_id)->first();

                
                if(!$order){
                    return response()->json([
                        'message' => trans('general.payment_does_not_exist'),
                    ]);
                }

                $shop = Shop::where('id', $order->shop_id)->firstOrFail();
                $categories = \App\Models\MenuItem::where('shop_id', $shop->id)->get();
                $plugins = PurchasedPlugins::where([
                    'shop_id' => $shop->id,
                    'user_id' => $shop->user_id,
                ])->get();
        
                $shipping_rates = ShippingRates::where([
                    'shop_id' => $shop->id,
                    'enabled' => 'yes',
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
        
                $cart = Cart::where([
                    'user_id' => auth()->guard('customers')->user()->id,
                    'shop_id' => $shop->id,
                ])->get();

                //random 4 products for related products
                $related_products = Products::inRandomOrder()
                ->where('shop_id', $shop->id)
                ->where('deleted', 'no')
                ->take(4)
                ->get();


                if($order->finished == 'no'){
                    //sent notification to shop owner
                    $this->sendNotification($shop->user_id, $shop->id, '1', $order->amount);

                    //send notification via email
                    $user = User::where('id', $shop->user_id)->first();
                    $order_url = 'https://mjegulla.com/order/'.$order->id;
                }

                //added to check if we should increment the balance on user or not
                $order->finished = 'yes';
                $order->save();

                //decrement quantity left of every variant that was in the order, and add a sale to the product.
                foreach ($cart as $item) {
                    $v_id = $item->variant_id;
                    $var = Variants::where('variant_id', $v_id)->first(); 
                    $prod = Products::where('id', $item->product_id)->first();

                    if ($var) { 
                        $var->decrement('quantity_left', 1); 
                    }
                    if($prod){
                        $prod->increment('product_sales', 1);
                        $prod->decrement('quantity', 1);
                    }
                }                       
                //delete cart products, after completed order.
                foreach ($cart as $cart) {
                    $cart->delete();
                }

                
                if($order){
                    return view('shops.theme_'.$shop->theme.'.order_completed',[
                        'shop' => $shop,
                        'categories' => $categories,
                        'header_message' => $shop->header_message,
                        'social_networks' => json_decode($shop->social_networks, true),
                        'social_switch' => $shop->social_networks,
                        'plugins' => $plugins,
                        'wishlist_count' => $wishlist_count,
                        'wishlist' => $wishlist,
                        'cart_count' => $cart_count,
                        'cart' => $cart,
                        'related_products' => $related_products,
                        'shipping_rates' => $shipping_rates,
                    ])->with('success', true);
                }else{
                    return view('shops.theme_'.$shop->theme.'.order_completed',[
                        'shop' => $shop,
                        'categories' => $categories,
                        'header_message' => $shop->header_message,
                        'social_networks' => json_decode($shop->social_networks, true),
                        'social_switch' => $shop->social_networks,
                        'plugins' => $plugins,
                        'wishlist_count' => $wishlist_count,
                        'wishlist' => $wishlist,
                        'cart_count' => $cart_count,
                        'cart' => $cart,
                        'related_products' => $related_products,
                        'shipping_rates' => $shipping_rates,
                    ])->with('error', true);
                }
            }
        }



        public function deleteCartProduct(Request $request, $product_id)
        {
            $product_id = $request->product_id;
            $user = auth()->guard('customers')->user();
        
            $cartProduct = Cart::where('product_id', $product_id)
                ->where('user_id', $user->id)
                ->first();
        
            if ($cartProduct) {
                // Determine the number of products in the cart for the user
                $cartProductCount = Cart::where('user_id', $user->id)->where('product_id', $product_id)->count();
        
                $isLastProduct = ($cartProductCount === 1); // Check if this is the last product in the cart
                $cartProduct->delete();
        
                return response()->json([
                    'status' => true,
                    'message' => trans('general.product_deleted_from_cart'),
                    'last' => $isLastProduct
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => trans('general.there_was_an_error'),
                ]);
            }
        }
        
        


}
