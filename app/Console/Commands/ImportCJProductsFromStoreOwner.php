<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Shop;
use App\Models\CjDropshipping;
use App\Models\Products;
use App\Models\ProductImages;
use App\Models\Variants;
use App\Models\ImportedProducts;
use Harimayco\Menu\Models\Menus;
use Harimayco\Menu\Models\MenuItems;

class ImportCJProductsFromStoreOwner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storeOwner:ImportCJProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CJ Products from store owner on its own store';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $imported_products = ImportedProducts::all();

        foreach($imported_products as $product){
            sleep(1);
            $shop = Shop::find($product->shop_id);
            $token = CjDropshipping::where('user_id', $product->user_id)->where('shop_id', $product->shop_id)->value('generated_token');
            if($shop->products_available > 0 || $shop->products_available == 'unlimited'){
                $this->import_product($product->product_id, $product->shop_id, $product->user_id, $token);
            }
            $product->delete();
        }
    }



    public function import_product($pid, $shop_id, $user_id, $token)
    {
        $cj = CjDropshipping::where('user_id', $user_id)->firstOrFail();
        $cj_product = $this->cj_view_product($token, $pid);


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
            return $response->json();//false;
        }
    }


}
