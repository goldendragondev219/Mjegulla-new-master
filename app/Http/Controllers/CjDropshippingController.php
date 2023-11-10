<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\CjDropshipping;
use App\Models\Shop;
use App\Models\Products;
use App\Models\ProductImages;
use App\Models\Categories;
use App\Models\Variants;
use App\Models\Orders;
use Harimayco\Menu\Models\Menus;
use Harimayco\Menu\Models\MenuItems;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class CjDropshippingController extends Controller
{

    public function CjDropshippingEditView()
    {
        $id = auth()->user()->managing_shop;
        if(auth()->check()){
            //check if shop belongs to the user 
            $shop = Shop::where([
                'id' => $id,
                'user_id' => auth()->user()->id
            ])->first();

            if($shop){
                $cj_data = CjDropshipping::where('shop_id', auth()->user()->managing_shop)
                ->where('user_id', auth()->user()->id)->first();
                if($cj_data){
                    $cj_balance = $this->cj_balance($cj_data->generated_token);
                }else{
                    $cj_balance = 0;
                }
                
                return view('cj_dropshipping.edit',[
                    'cj_data' => $cj_data,
                    'cj_balance' => $cj_balance,
                ]);
            }

        }//end check auth
    }


    public function update(Request $request)
    {
        $data = $request->validate([
            'cj_email' => 'required|email|max:100',
            'cj_api_key' => 'required|string',
        ]);
        
        $email = $request->cj_email;
        $api_key = $request->cj_api_key;
    
        $userId = auth()->user()->id;
    
        // Try to get the access token from the cache
        $accessToken = Cache::get('cj_access_token_' . $userId);
        $lastRequestTime = Cache::get('cj_last_request_time_' . $userId);
    
        // Check if the access token is not in the cache or if it's expired
        if (!$accessToken || time() - $lastRequestTime >= 300) {
            $cj_data = $this->get_access_token($email, $api_key);
            
            // Store the access token and update the last request time in the cache
            Cache::put('cj_access_token_' . $userId, $accessToken, 300); // Cache for 5 minutes (300 seconds)
            Cache::put('cj_last_request_time_' . $userId, time(), 300);

            if ($cj_data) {
                if (!isset($cj_data) || !isset($cj_data['data']) || !isset($cj_data['data']['accessToken'])) {
                    // data is null lreturn with error message
                    return redirect()->back()->withErrors(trans('general.alert_title_validation_error'));
                }

                // The array and its offsets exist, so you can safely access $accessToken.
                $accessToken = $cj_data['data']['accessToken'];

                $accessTokenExpiryDate = Carbon::parse($cj_data['data']['accessTokenExpiryDate'])->tz('Europe/Paris');
    
                $cj_db = new CjDropshipping;
                $cj_db->user_id = $userId;
                $cj_db->shop_id = auth()->user()->managing_shop;
                $cj_db->email = $email;
                $cj_db->api_key = $api_key;
                $cj_db->generated_token = $accessToken;
                $cj_db->token_exp_date = $accessTokenExpiryDate;
                $cj_db->save();
            } else {
                return redirect()->back()->withErrors(trans('general.cj_limit_requests'));
            }
        } else {
            return redirect()->back()->withErrors(trans('general.cj_limit_requests'));
        }
    
        return redirect()->back()->with('success', trans('general.cj_api_success'));
    }
    



    public function get_access_token($email, $api_key)
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $clientIP = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $clientIP = $_SERVER['REMOTE_ADDR'];
        }
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Forwarded-For' => $clientIP, // Include the client's IP address
        ])
            ->post('https://developers.cjdropshipping.com/api2.0/v1/authentication/getAccessToken', [
                'email' => $email,
                'password' => $api_key,
            ]);
        
        // Check if the request was successful (status code 200)
        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        }
    }
    


    public function delete($id)
    {
        $cj = CjDropshipping::where('id', $id)->firstOrFail();
        if($cj->user_id == auth()->user()->id){
            $cj->delete();
            return redirect()->back()->with('success', trans('general.cj_api_del_success'));
        }else{
            return redirect()->back()->withErrors(trans('general.there_was_an_error'));
        }
    }



    //Get categories
    public function cj_categories($token)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->get('https://developers.cjdropshipping.com/api2.0/v1/product/getCategory');

        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        }
    }


    //Get products
    public function cj_products($token, $query)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->get('https://developers.cjdropshipping.com/api2.0/v1/product/list', $query);

        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        }
    }

    public function cj_view_product($token, $pid)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->get('https://developers.cjdropshipping.com/api2.0/v1/product/query', [
                'pid' => $pid,
            ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        }
    }


    public function cj_balance($token)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->get('https://developers.cjdropshipping.com/api2.0/v1/shopping/pay/getBalance');

        if ($response->successful()) {
            $data = $response->json();
            return $data['data']['amount'];
        } else {
            return false;
        }
    }


    public function shipping_calculator($token, $data)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->post('https://developers.cjdropshipping.com/api2.0/v1/logistic/freightCalculate', $data);
    
        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        }
    }
    

    public function cj_create_order($token, $data)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->post('https://developers.cjdropshipping.com/api2.0/v1/shopping/order/createOrder', $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        }
    }


    public function cj_confirm_order($token, $order_id)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->patch('https://developers.cjdropshipping.com/api2.0/v1/shopping/order/confirmOrder', [
                'orderId' => $order_id,
            ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        }
    }


    public function cj_pay_balance($order_id, $token)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->post('https://developers.cjdropshipping.com/api2.0/v1/shopping/pay/payBalance', [
                'orderId' => $order_id,
            ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        }
    }


    public function query_order($order_id, $token)
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])
            ->get('https://developers.cjdropshipping.com/api2.0/v1/shopping/order/getOrderDetail', [
                'orderId' => $order_id,
            ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return false;
        } 
    }


    public function index(Request $request)
    {
        if(!auth()->user()->active_cj_d()){
            return redirect()->route('cj_dropshipping_edit')->withErrors(trans('general.cj_d_edit_description'));
        }
        $cj = CjDropshipping::where('user_id', auth()->user()->id)->firstOrFail();
        $cj_categories = $this->cj_categories($cj->generated_token);

        if($request->sku === 'true'){
            $query_products = array(
                'productSku' => $request->cj_search,
                'pageNum' => $request->page,
                'pageSize' => '48',
            );
        }else{
            $query_products = array(
                'productNameEn' => $request->cj_search,
                'categoryId' => $request->cid,
                'pageNum' => $request->page,
                'pageSize' => '48',
            );
        }



        $cj_products = $this->cj_products($cj->generated_token, $query_products);
        return view('cj_dropshipping.index',[
            'cj_categories' => $cj_categories,
            'cj_products' => $cj_products,
        ]);
    }


    public function viewProduct($pid)
    {
        $cj = CjDropshipping::where('user_id', auth()->user()->id)->firstOrFail();
        $cj_product = $this->cj_view_product($cj->generated_token, $pid);
        if(!isset($cj_product['data']['productNameEn'])){
            abort(404);
        }
        return view('cj_dropshipping.view_product',[
            'cj_product' => $cj_product,
        ])->render();
    }


    public function import_product($pid)
    {
        $shop_id = auth()->user()->managing_shop;
        $user_id = auth()->user()->id;
        $cj = CjDropshipping::where('user_id', $user_id)->firstOrFail();
        $cj_product = $this->cj_view_product($cj->generated_token, $pid);

        $check_product_sku = Products::where([
            'product_sku' => $cj_product['data']['productSku'],
            'shop_id' => $shop_id,
        ])->first();

        if($check_product_sku){
            return response()->json([
                'status' => false,
                'message' => trans('general.cj_product_sku_exist'),
            ]);
        }

        //create the product
        $product = new Products();
        $product->user_id = $user_id;
        $product->shop_id = $shop_id;
        $product->product_name = $cj_product['data']['productNameEn'];
        $product->product_description = $cj_product['data']['description'];
        $product->variant_id = 'cj_dropshipping';
        $product->product_sku = $cj_product['data']['productSku'];

        $prod_quantity = 0;
        foreach($cj_product['data']['variants'] as $variants){
            $prod_quantity += $variants['variantVolume'];
        }

        $product->quantity = $prod_quantity;
        $product->base_price = (strpos($cj_product['data']['sellPrice'], '-') !== false) ? max(array_map('floatval', explode('-', $cj_product['data']['sellPrice']))) : floatval($cj_product['data']['sellPrice']);
        $product->product_seo_keywords = str_replace(['&', '/', '>', '<'], ',', $cj_product['data']['categoryName']);

        //Create product URL
        $product_url = preg_replace('/[^A-Za-z0-9-]+/', '-', $cj_product['data']['productNameEn']);
        $product_url = substr($product_url, 0, 191);
        $product_url = strtolower($product_url);
        
        //Check if product url already exist.
        $existingProduct = Products::where([
            'product_url' => $product_url,
            ])->first();

        if ($existingProduct) {
            // Product URL already exists in database and is associated with a different product
            $product_url = $product_url.'-'.Str::uuid();
        }

        $product->product_url = $product_url;

        //Added temp Category
        [$product_temp_cat] = $product_temp_cat = str_replace("'", "", explode(' ', $cj_product['data']['categoryName']));
        
        //Category model
        $category = MenuItems::where('user_id', $user_id)
        ->where('shop_id', $shop_id)
        ->where('label', $product_temp_cat)
        ->first();
    
        if ($category) {
            // Category exists
            $product->product_category = $category->id;
        } else {
            $menu = Menus::where('user_id', $user_id)->where('shop_id', $shop_id)->first();
            // Create a new category
            $newCategory = new MenuItems();
            $newCategory->user_id = $user_id;
            $newCategory->shop_id = $shop_id;
            $newCategory->menu = $menu->id;
            $newCategory->label = $product_temp_cat;
            $newCategory->link = preg_replace('/[^a-zA-Z0-9\-]+/', '-', $product_temp_cat);
            $newCategory->save();
        
            $product->product_category = $newCategory->id;
        }
        
        $product->product_single_image = $cj_product['data']['productImageSet'][0];
        $product->save();


        //create product images
        foreach($cj_product['data']['productImageSet'] as $prod_images){
            $product_images = new ProductImages();
            $product_images->user_id = $user_id;
            $product_images->shop_id = $shop_id;
            $product_images->product_id = $product->id;
            $product_images->image_url = $prod_images;
            $product_images->save();
        }


        //create product variants
        $cj_product_variants = $cj_product['data']['variants'];

        foreach ($cj_product_variants as $p_variants) {
            $db_variants = new Variants();
            $db_variants->variant_id = $p_variants['vid'];
            $db_variants->product_sku = $p_variants['variantSku'];
            $db_variants->user_id = $user_id;
            $db_variants->shop_id = $shop_id;
            $db_variants->product_id = $product->id;
            $db_variants->size = strpos($p_variants['variantKey'], '-') !== false ? substr(strstr($p_variants['variantKey'], '-'), 1) : $p_variants['variantKey']; // get value after -
            $db_variants->price = $p_variants['variantSellPrice'];
            $db_variants->quantity = $p_variants['variantVolume'];
            $db_variants->quantity_left = $p_variants['variantVolume'];
            $db_variants->color = strpos($p_variants['variantKey'], '-') !== false ? strstr($p_variants['variantKey'], '-', true) : ''; // get value before the - , if no - return empty ''
            $db_variants->save();
        }
        

        $shop = Shop::find($shop_id);
        //decrement shop's available products if not unlimited
        if ($shop->products_available !== 'unlimited') {
            $shop->decrement('products_available', 1);
        }

        //increment total products
        $shop->increment('total_products', 1);


        return response()->json([
            'status' => true,
            'message' => trans('general.cj_product_imported_success'),
        ]);

    }


    public function payOrderBalance($orderId)
    {
        $order = Orders::find($orderId);
        $shop = Shop::find($order->shop_id);

        if($shop->user_id !== auth()->user()->id){
            abort(403); //this is not user's order no access..
        }

        if (!$order) {
            abort(404); // Order not found, handle this case appropriately
        }

        if ($order->cj_paid === 'no') {
            $token = CjDropshipping::where('user_id', auth()->id())->value('generated_token');
            $result = $this->cj_pay_balance($orderId, $token);

            if ($result['result']) {
                $order->cj_paid = 'yes';
                $order->save();
            } else {
                $order->cj_paid = 'no';
                $order->save();

                return redirect()->route('cj_dropshipping_edit', auth()->user()->managing_shop)
                    ->withErrors(trans('general.cj_low_balance'));

                // Send an email to the shop owner to notify them about the payment issue
            }
        }else{
            return redirect()->back();
        }

        // Handle any other logic here, if needed
    }



}
