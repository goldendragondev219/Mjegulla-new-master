<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;




use App\Models\Products;
use App\Models\Shop;
use App\Models\ProductImages;
use App\Models\Categories;
use App\Models\Variants;
use App\Models\ShippingRates;
use App\Models\Cart;
use App\Models\MenuItem;
use App\Models\Wishlist;

use App\Models\ImportedProducts;
use App\Models\CollectionProducts;

class ProductsController extends Controller
{
    public function view()
    {
        if (auth()->user()->managing_shop) {
            // Get the shop by ID
            $id = auth()->user()->managing_shop;
            $shop = Shop::find($id);

            // Check if the shop exists and belongs to the currently authenticated user
            if ($shop && $shop->user_id === auth()->user()->id) {
                // The shop belongs to the user
                return view('products',
                [
                    'id' => $id,
                    'shop_id' => $shop->id,
                    'shop_name' => $shop->shop_name,
                    'shop_url' => ($shop->custom_domain_activated) ? "https://{$shop->custom_domain}" : "https://{$shop->my_shop_url}." . parse_url(config('app.url'), PHP_URL_HOST),
                    'shop_categories' => auth()->user()->shops()->find($shop->id)->categories($shop->id),
                    'products' => auth()->user()->product()->where('shop_id', $shop->id)->orderBy('id', 'DESC')->paginate(20),
                ]);
            } else {
                abort(401, 'Unauthorized');
            }
        }

    }


    public function newView()
    {
        if(auth()->user()->managing_shop){
            if(auth()->user()->isDropshipping()){
                return redirect()->route('cj_dropshipping_index', auth()->user()->managing_shop);
            }
            $id = auth()->user()->managing_shop;
            $shop = Shop::find($id);
            if ($shop && $shop->user_id === auth()->user()->id) {
                return view('newProduct',[
                    'shop_categories' => auth()->user()->shops()->find($shop->id)->categories($shop->id),
                    'id' => $shop->id,
                    'shop_id' => $shop->id,
                ]);
            }else{
                abort(401, 'Unauthorized');
            }
        }
    }


    public function create(Request $request, $id)
    {
        $shop = Shop::find($id);
        if ($shop && $shop->user_id === auth()->user()->id) {

            $data = $request->validate([
                'product_name' => 'required|string|min:4|max:191',
                'category' => 'required|exists:admin_menu_items,id',
                'sub_category' => 'nullable|exists:admin_menu_items,id',
                'product_description_input' => 'required|string|min:10|max:20000',
                'product_seo_keywords' => 'string|min:4|max:200',
                'product-images.*' => 'required|image|mimes:jpeg,png,jpg,JPG,gif|max:2048',
                'featured_image' => 'required|image|mimes:jpeg,png,jpg,JPG,gif|max:2048',
                'variants.*.size' => 'string|min:1|max:10',
                'variants.*.color' => 'string|min:1|max:20',
                'variants.*.price' => 'numeric|min:5|max:1000|between:5,500',
                'variants.*.stock' => 'numeric|min:1',
                'base_price' => 'required|numeric|min:0|max:1000|between:5,500',
                'base_price_discount' => 'nullable|numeric|min:0|max:1000|between:0,100',
                'product_sku' => 'required|string|min:4|max:200|unique:products,product_sku,NULL,id,user_id,' . auth()->user()->id,
            ]);

            //check if it's my category
            $cat = MenuItem::where('id', $request->category)->where('user_id', auth()->user()->id)->where('shop_id', auth()->user()->managing_shop)->first();
            if(!$cat){
                abort(401, 'Unauthorized');
            }
            if($cat->user_id != auth()->user()->id){
                abort(401, 'Unauthorized');
            }

            if ($request->sub_category !== null) {
                // Check if it's my sub-category
                $sub_cat = MenuItem::where('id', $request->sub_category)
                    ->where('user_id', auth()->user()->id)
                    ->where('shop_id', auth()->user()->managing_shop)
                    ->first();
            
                if (!$sub_cat) {
                    abort(401, 'Unauthorized');
                }
            
                if ($sub_cat->user_id !== auth()->user()->id) {
                    abort(401, 'Unauthorized');
                }
            }


            $product_url = preg_replace('/[^A-Za-z0-9-]+/', '-', $request->product_name);
            $product_url = substr($product_url, 0, 191);
            $product_url = strtolower($product_url);

            $existingProduct = Products::where([
                'product_url' => $product_url,
                ])->first();

            if ($existingProduct) {
                // Product URL already exists in database and is associated with a different product
                $product_url = $product_url.'-'.Str::uuid();
            }

            //update featured image
            if($request->hasFile('featured_image')) {
                $featured_image = $request->file('featured_image');
                $featured_image_name = Str::uuid() . '_' . $featured_image->getClientOriginalName();
                Storage::disk('s3')->put('products/images/' . $featured_image_name, file_get_contents($featured_image), 'public');
                $featured_image = 'https://cdn.mjegulla.com/products/images/'.$featured_image_name;
            }else{
                $featured_image = '0';
            }





            $product_create = auth()->user()->product()->create([
                'user_id' => auth()->user()->id,
                'shop_id' => $id,
                'variant_id' => '0',
                'quantity' => '0',
                'product_name' => $request->product_name,
                'product_category' => $request->category != "null" ? $request->category : null,
                'admin_menu_item_id' => $request->sub_category != "null" ? $request->sub_category : null,
                'product_description' => $request->product_description_input,
                'product_seo_keywords' => $request->product_seo_keywords,
                'product_url' => $product_url,
                'product_single_image' => $featured_image,
                'base_price' => $request->base_price,
                'base_price_discount' => $request->base_price_discount,
                'product_sku' => $request->product_sku,
            ]);

            $variants = json_decode($request->input('variants'), true);
            $variant_ids = array();
            $product_quantity = 0;
            $variant_price_for_product = 0;
            foreach ($variants as $variant) {
                $size = $variant['variants[size]'];
                $price = $variant['variants[price]'];
                $quantity = $variant['variants[quantity]'];
                $color = $variant['variants[color]'];
                $material = $variant['variants[material]'];
                $variant_uuid = Str::uuid();
                $variant_ids[] = $variant_uuid;

                if (is_numeric($quantity) && $quantity != 0) {
                    $product_quantity += $quantity;
                }else{
                    $quantity = 0;
                }

                if (is_numeric($price) && $price != 0) {
                    $price = $price;
                }else{
                    $price = 0;
                }

                // $color = explode('/', str_replace(',', '/', $color));
                // $color = json_encode($color);


                Variants::create([
                    'variant_id' => $variant_uuid,
                    'user_id' => auth()->user()->id,
                    'shop_id' => $id,
                    'product_id' => $product_create->id,
                    'product_sku' => $request->product_sku,
                    'size' => $size,
                    'price' => $price,
                    'quantity' => $quantity,
                    'quantity_left' => $quantity,
                    'color' => $color,
                    'material' => $material,
                ]);
            }

            //update variant ids
            $product_create->variant_id = $variant_ids;
            $product_create->quantity = $product_quantity;
            $product_create->save();



            if ($request->hasFile('product-images')) {
                $images = $request->file('product-images');
                $successCount = 0;
                foreach ($images as $image) {
                    $filename =  Str::uuid() . '_' . $image->getClientOriginalName();
                    $upload = Storage::disk('s3')->put('products/images/' . $filename, file_get_contents($image), 'public');
                    if ($upload) {
                        $successCount++;
                        $save_product_images = ProductImages::create([
                            'user_id' => auth()->user()->id,
                            'shop_id' => $id,
                            'product_id' => $product_create->id,
                            'image_url' => 'https://cdn.mjegulla.com/products/images/'.$filename,
                        ]);
                    }
                }
                if ($successCount == count($images)) {
                    // All files were uploaded successfully
                    //return response()->json(['success' => true]);

                } else {
                    // Handle the case where one or more files were not uploaded
                    return redirect()->back()->withErrors(trans('general.file_upload_fail'));
                }
            }

            //decrement shop's available products if not unlimited
            if ($shop->products_available !== 'unlimited') {
                $shop->decrement('products_available', 1);
            }

            //increment total products
            $shop->increment('total_products', 1);

            if ($product_create) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product created successfully.',
                    'url' => route('manage_product', $product_create->id),
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.there_was_an_error'),
                ], 400);
            }

        } else {
            abort(401, 'Unauthorized');
        }

    }



    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if($product->user_id === auth()->user()->id){
            $data = $request->validate([
                'product_name' => 'required|string|min:4|max:191',
                'category' => 'required|exists:admin_menu_items,id',
                'sub_category' => 'nullable|exists:admin_menu_items,id',
                'product_description_input' => 'required|string|min:10|max:20000',
                'product_seo_keywords' => 'string|min:4|max:200',
                'xk_shipping_price' => 'numeric|min:0|max:1000|between:0,100',
                'al_shipping_price' => 'numeric|min:0|max:1000|between:0,100',
                'mk_shipping_price' => 'numeric|min:0|max:1000|between:0,100',
                'base_price' => 'required|numeric|min:0|max:1000|between:5,500',
                'base_price_discount' => 'nullable|numeric|min:0|max:1000|between:0,100',
                'product_sku' => 'required|string|min:4|max:200|unique:products,product_sku,' . $product->id . ',id,user_id,' . auth()->user()->id,
            ]);

            //check if it's my category
            $cat = MenuItem::where('id', $request->category)->where('user_id', auth()->user()->id)->where('shop_id', auth()->user()->managing_shop)->first();
            if(!$cat){
                abort(401, 'Unauthorized');
            }
            if($cat->user_id != auth()->user()->id){
                abort(401, 'Unauthorized');
            }

            if ($request->sub_category !== null) {
                // Check if it's my sub-category
                $sub_cat = MenuItem::where('id', $request->sub_category)
                    ->where('user_id', auth()->user()->id)
                    ->where('shop_id', auth()->user()->managing_shop)
                    ->first();
            
                if (!$sub_cat) {
                    abort(401, 'Unauthorized');
                }
            
                if ($sub_cat->user_id !== auth()->user()->id) {
                    abort(401, 'Unauthorized');
                }
            }
            
            

            $product->product_name = $request->product_name;
            $product->product_category = $request->category;
            $product->admin_menu_item_id = $request->sub_category;
            $product->product_description = $request->product_description_input;
            $product->product_seo_keywords = $request->product_seo_keywords;
            $product->base_price = $request->base_price;
            $product->base_price_discount = $request->base_price_discount;
            $product->product_sku = $request->product_sku;
            $updated = $product->save();


            if($updated){
                $variants = Variants::where('product_id', $product->id)->get();
                if($variants->isNotEmpty()){
                    $variants->each(function ($variant) use ($request) {
                        $variant->product_sku = $request->product_sku;
                        $variant->save();
                    });
                }
                return redirect()->back()->with('success', trans('general.product_updated_successfully'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }

        }else{
            abort(401, 'Unauthorized');
        }
    }



    public function manage($id)
    {
        $product = Products::where('id', $id)->where('deleted', 'no')->firstOrFail();

        if($product && $product->user_id === auth()->user()->id){
            $shop = Shop::find($product->shop_id);
            $product_images = optional(ProductImages::where('product_id', $product->id)->where('deleted', 'no')->orderBy('id', 'DESC')->get())->toArray();
            $product_variants = optional(Variants::where('product_id', $product->id)->where('deleted', 'no')->get())->toArray();
            return view('manageProduct',
            [
                'id' => $product->id,
                'shop_id' => $product->shop_id,
                'product_name' => $product->product_name,
                'shop_url' => ($shop->custom_domain_activated) ? "https://{$shop->custom_domain}" : "https://{$shop->my_shop_url}." . parse_url(config('app.url'), PHP_URL_HOST),
                'product_category' => $product->product_category,
                'product_admin_menu_item_id' => $product->admin_menu_item_id,
                'shop_categories' => auth()->user()->shops()->find($product->shop_id)->categories($product->shop_id),
                'product_description' => $product->product_description,
                'product_seo_keywords' => $product->product_seo_keywords,
                'product_url' => $product->product_url,
                'quantity' => $product->quantity,
                'product_variants' => $product_variants,
                'product_sales' => $product->product_sales,
                'product_views' => $product->product_views,
                'featured_image' => $product->product_single_image,
                'product_images' => $product_images,
                'base_price' => $product->base_price,
                'base_price_discount' => $product->base_price_discount,
                'product_sku' => $product->product_sku,
            ]);
        }else{
            abort(401, 'Unauthorized');
        }
    }



    public function deleteImage($id)
    {
        $image = ProductImages::find($id);

        if($image && $image->user_id === auth()->user()->id){
            $delete = $image->delete();
            if (Storage::exists('public/images/' . $image->image_url)) {
                $delete = Storage::delete('public/images/' . $image->image_url);
            }
            if($delete) {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('general.image_deleted_successfully'),
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.there_was_an_error'),
                ], 400);
            }
        }else{
            abort(401, 'Unauthorized');
        }
    }


    public function deleteFeaturedImage($id)
    {
        $product = Products::findOrFail($id);


        if($product && $product->user_id === auth()->user()->id){
            if (Storage::exists('public/images/' . $product->product_single_image)) {
                $delete = Storage::delete('public/images/' . $product->product_single_image);
            }
            $product->product_single_image = '';
            $update = $product->save();
            if($update) {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('general.image_deleted_successfully'),
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.there_was_an_error'),
                ], 400);
            }
        }else{
            abort(401, 'Unauthorized');
        }
    }


    public function deleteProduct($id)
    {
        $product = Products::find($id);
        if(!is_null($product) && $product->user_id === auth()->user()->id){
            $product_images = ProductImages::where('product_id', $id)->get();
            if(!empty($product_images)){
                foreach($product_images as $imgs){
                    Storage::delete('public/images/' . $imgs->image_url);
                    $imgs->deleted = 'yes';
                    $imgs->save();
                }
            }
            $product->deleted = 'yes';
            $deleted = $product->save();
            if($deleted){
                Variants::where('product_id', $product->id)->update(['deleted' => 'yes']);
                Cart::where(['product_id' => $product->id, 'shop_id' => $product->shop_id])->delete();
                Wishlist::where(['product_id' => $product->id, 'shop_id' => $product->shop_id])->delete();
                CollectionProducts::where('product_id', $product->id)->where('shop_id', $product->shop_id)->delete();

                //increment the product availability, if not unlimited.
                $shop = Shop::where('id', $product->shop_id)->first();
                $shop->decrement('total_products', 1);
                if ($shop->products_available !== 'unlimited') {
                    $shop->increment('products_available', 1);
                }
                return redirect()->back()->with('success', trans('general.product_deleted_successfully'));
            }else{
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }
        }else{
            abort(401, 'Unauthenticated');
        }
    }


    public function updateProductUrl(Request $request, $id)
    {
        $data = $request->validate([
            'product_url' => 'required|string|min:4|max:191',
        ]);
        $product = Products::findOrFail($id);
        if($product->user_id === auth()->user()->id){
            $product_url = preg_replace('/[^A-Za-z0-9-]+/', '-', $request->product_url);
            $product_url = substr($product_url, 0, 191);
            $product_url = strtolower($product_url);

            $existingProduct = Products::where([
                'product_url' => $product_url,
                ])->first();

            if ($existingProduct && $existingProduct->id !== $product->id) {
                // Product URL already exists in database and is associated with a different product
                return response()->json('url_already_in_use');
            }else{
                // Product URL is unique
                $product->product_url = $product_url;
                $product->save();

                return response()->json($product_url);
            }

        }else{
            abort(401, 'Unauthenticated');
        }
    }



    //edit product featured image
    public function editFeaturedImage(Request $request, $id)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,JPG,gif|max:5048',
        ]);
        $product = Products::findOrFail($id);
        if($product->user_id === auth()->user()->id){
            if($request->hasFile('image')){
                $featured_image = $request->file('image');
                $filename =  Str::uuid() . '_' . $featured_image->getClientOriginalName();
                $uploaded = Storage::disk('s3')->put('products/images/' . $filename, file_get_contents($featured_image), 'public');
                if($uploaded){
                    $product->product_single_image = 'https://cdn.mjegulla.com/products/images/'.$filename;
                    $save_product = $product->save();
                    if($save_product){
                        return response()->json([
                            'status' => 'success',
                            'message' => trans('general.image_uploaded_successfully'),
                        ], 200);
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message' => trans('general.there_was_an_error'),
                        ], 400);
                    }
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => trans('general.there_was_an_error'),
                    ], 400);
                }
            }
        }else{
            abort(401, 'Unauthenticated');
        }
    }


    //edit product images
    public function editImages(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        if($product->user_id === auth()->user()->id){
            if(auth()->user()->id == '46285' || auth()->user()->id == '46278' || auth()->user()->id == '1'){
                $data = $request->validate([
                    'product-images.*' => 'required|mimetypes:video/mp4,image/jpeg,image/png,image/jpg|max:5048',
                ]);
            }else{
                $data = $request->validate([
                    'product-images.*' => 'required|image|mimes:jpeg,png,jpg,JPG|max:2048',
                ]);
            }

            if ($request->hasFile('product-images')) {
                $images = $request->file('product-images');
                $successCount = 0;
                $response_imgs = [];
                foreach ($images as $image) {
                    $filename =  Str::uuid() . '_' . $image->getClientOriginalName();
                    $uploaded = Storage::disk('s3')->put('products/images/' . $filename, file_get_contents($image), 'public');
                    if ($uploaded) {
                        $successCount++;
                        try {
                            $save_product_images = ProductImages::create([
                                'user_id' => auth()->user()->id,
                                'shop_id' => $id,
                                'product_id' => $product->id,
                                'image_url' => 'https://cdn.mjegulla.com/products/images/'.$filename,
                            ]);
                            $response_imgs[] = [
                                'image_name' => 'https://cdn.mjegulla.com/products/images/'.$filename,
                                'image_id' => $save_product_images->id,
                            ];
                        } catch (\Throwable $th) {
                            return response()->json([
                                'status' => 'error',
                                'message' => trans('general.there_was_an_error'),
                            ], 400);
                        }
                    }
                }
                if ($successCount == count($images)) {
                    // All files were uploaded successfully
                    return response()->json([
                        'status' => 'success',
                        'message' => trans('general.image_uploaded_successfully'),
                        'data' => $response_imgs,
                    ], 200);
                } else {
                    // Handle the case where one or more files were not uploaded
                    return response()->json([
                        'status' => 'error',
                        'message' => trans('general.there_was_an_error'),
                    ], 400);
                }
            }

        }else{
            abort(401, 'Unauthenticated');
        }
    }



    public function editVariant(Request $request, $variant_id)
    {
        $variant = Variants::where('id', $variant_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if(!$variant){
            abort(401, 'Unauthenticated');
        }


        $data = $request->validate([
            'variant_size' => 'string|min:1|max:50',
            'variant_price' => 'required|numeric|min:5|max:1000|between:5,500',
            'variant_quantity' => 'required|numeric|min:1',
            'variant_material' => 'nullable|string|min:1|max:100',
        ]);

        $variant->size = $request->input('variant_size');
        $variant->price = $request->input('variant_price');
        $oldQuantity = $variant->quantity;
        $variant->quantity = $request->input('variant_quantity');
        $variant->color = $request->input('variant_color');
        $variant->material = $request->input('variant_material');

        $saved = $variant->save();
        if($saved && $oldQuantity != $variant->quantity){
            $quantityDiff = $variant->quantity - $oldQuantity;
            $variant->increment('quantity_left', $quantityDiff);
            Products::where('id', $variant->product_id)->increment('quantity', $quantityDiff);
        }

        return response()->json([
            'success' => true,
            'message' => trans('general.variant_updated_successfully'),
        ]);
    }



    public function deleteVariant($id)
    {
        $variant = Variants::findOrFail($id);

        if($variant->user_id != auth()->user()->id){
            abort(401, 'Unauthenticated');
        }

        $product = Products::findOrFail($variant->product_id);

        $count_product_variants = Variants::where('product_id', $product->id)->where('deleted', 'no')->count();

        if($count_product_variants == 1){
            return response()->json([
                'success' => false,
                'message' => trans('general.last_variant_delete_error'),
            ]);
        }

        $product->decrement('quantity', $variant->quantity_left);

        if($variant->update(['deleted' => 'yes'])){
            //Delete product from client cart, and wishlist
            Cart::where('product_id', $variant->product_id)->delete();
            Wishlist::where('product_id', $variant->product_id)->delete();
            return response()->json([
                'success' => true,
                'message' => trans('general.variant_deleted_successfully'),
            ]);
        }
    }



    //create single variant
    public function createVariant(Request $request, $product_id)
    {
        $product = Products::findOrFail($product_id);

        if($product->user_id != auth()->user()->id){
            abort(401, 'Unauthenticated');
        }

        $data = $request->validate([
            'size' => 'string|min:1|max:10',
            'price' => 'required|numeric|min:5|max:1000|between:0,100',
            'quantity' => 'required|numeric|min:1',
            'material' => 'nullable|string|min:1|max:100',
        ]);

        $variant = new Variants;
        $variant->variant_id = Str::uuid();
        $variant->user_id = auth()->user()->id;
        $variant->shop_id = $product->shop_id;
        $variant->product_id = $product->id;
        $variant->size = $request->input('size');
        $variant->price = $request->input('price');
        $variant->quantity = $request->input('quantity');
        $variant->quantity_left = $request->input('quantity');
        $variant->color = $request->input('color');
        $variant->material = $request->input('material');

        //set product_sku
        $variant->product_sku = $product->product_sku;

        if($variant->save()){
            $product->increment('quantity', $variant->quantity);

            //Update product variants
            $all_variants = json_decode($product->variant_id);
            $all_variants[] = $variant->variant_id;
            $product->variant_id = json_encode($all_variants);

            $product->save();


            return response()->json([
                'success' => true,
                'message' => trans('general.variant_added_successfully'),
                'variant' => $variant,
            ]);
        }

    }



    public function CJMassImport(Request $request)
    {
        $products = $request->products;
        $managingShop = auth()->user()->managing_shop;
        
        foreach ($products as $product) {
            // Check if the product_id for the given shop_id doesn't exist in the ImportedProducts table
            $existingEntry = ImportedProducts::where('product_id', $product)
                                            ->where('shop_id', $managingShop)
                                            ->exists();
        
            if (!$existingEntry) {
                $import = new ImportedProducts();
                $import->user_id = auth()->user()->id;
                $import->shop_id = $managingShop;
                $import->product_id = $product;
                $import->save();
            }
        }
        

        return response()->json([
            'status' => true,
            'message' => trans('general.products_will_start_adding_on_your_store'),
        ]);
    }

















}
