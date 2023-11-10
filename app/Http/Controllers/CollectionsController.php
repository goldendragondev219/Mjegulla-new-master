<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Products;
use App\Models\ProductImages;
use App\Models\Collections;
use App\Models\CollectionProducts;
use App\Models\Shop;

class CollectionsController extends Controller
{
    public function collections()
    {
        $collections = Collections::where('user_id', auth()->user()->id)
        ->where('shop_id', auth()->user()->managing_shop)
        ->paginate(10);
    
        $shop = Shop::where('id', auth()->user()->managing_shop)
        ->where('user_id', auth()->user()->id)
        ->firstOrFail();

        return view('collections.index',[
            'collections' => $collections,
            'shop_url' => ($shop->custom_domain_activated) ? "https://{$shop->custom_domain}" : "https://{$shop->my_shop_url}." . parse_url(config('app.url'), PHP_URL_HOST),
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'collectionName' => 'required|string|min:4|max:191',
        ]);
        $name = $request->collectionName;
        $user_id = auth()->user()->id;
        $shop_id = auth()->user()->managing_shop;
        $slug = Str::slug($name);

        $check_exist = Collections::where('slug', $slug)->first();

        if($check_exist){
            $slut = $slug.'-'.Str::uuid();
        }
        
        $c = new Collections();
        $c->name = $name;
        $c->user_id = $user_id;
        $c->shop_id = $shop_id;
        $c->slug = $slug;
        $c->save();
        return redirect()->back()->with('success', trans('general.collection_created'));
    }



    public function view($collection_id)
    {
        $collection = Collections::where('id', $collection_id)
            ->where('shop_id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();
    
        $collection_products = CollectionProducts::where('collection_id', $collection_id)
            ->where('shop_id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->orderBy('id', 'DESC')
            ->paginate(12);
    
        $already_exist_on_collection = CollectionProducts::where('collection_id', $collection_id)
            ->where('shop_id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->pluck('product_id')
            ->toArray(); // Retrieve product IDs as an array
    
        $all_my_products = Products::where('shop_id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->where('deleted', 'no')
            ->whereNotIn('id', $already_exist_on_collection) // Filter out product IDs
            ->orderBy('id', 'DESC')
            ->paginate(12);
    

        return view('collections.view', [
            'collection' => $collection,
            'collection_products' => $collection_products,
            'my_products' => $all_my_products,
        ]);
    }
    
    public function get_products(Request $request)
    {
        $collection_id = $request->collection_id;
    
        $collection_products = CollectionProducts::where('collection_id', $collection_id)
            ->where('shop_id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->pluck('product_id')
            ->toArray(); // Retrieve product IDs as an array
    
        $products = Products::where('shop_id', auth()->user()->managing_shop)
            ->where('user_id', auth()->user()->id)
            ->where('deleted', 'no')
            ->whereNotIn('id', $collection_products) // Filter out product IDs
            ->orderBy('id', 'DESC')
            ->paginate(12);
    
        return $products;
    }
    
    

    public function import(Request $request)
    {
        $collection_id = $request->collection_id;
        $products = $request->products;
        
        $collection = Collections::where('id', $collection_id)
        ->where('shop_id', auth()->user()->managing_shop)
        ->where('user_id', auth()->user()->id)
        ->first();
        

        if($collection){
            foreach($products as $product){
                $c = new CollectionProducts();
                $c->collection_id = $collection->id;
                $c->user_id = auth()->user()->id;
                $c->shop_id = auth()->user()->managing_shop;
                $c->product_id = $product;
                $c->save();
            }
            return response()->json([
                'status' => true,
                'message' => trans('general.import_success'),
            ]);
        }else{
            abort(403, 'Unauthorized');
        }
    }


    public function DeleteCollection($collection_id)
    {
        $collection = Collections::where('id', $collection_id)
        ->where('shop_id', auth()->user()->managing_shop)
        ->where('user_id', auth()->user()->id)
        ->first();

        if($collection){
            $collection->delete();
            return redirect()->back()->with('success', trans('general.collection_deleted'));
        }else{
            abort(403, 'Unauthorized');
        }
    }



    public function DeleteCollectionProduct($product_id, $collection_id)
    {
        $collection_product = CollectionProducts::where('collection_id', $collection_id)
        ->where('product_id', $product_id)
        ->where('shop_id', auth()->user()->managing_shop)
        ->where('user_id', auth()->user()->id)
        ->first();

        if($collection_product){
            $collection_product->delete();
            return redirect()->back()->with('success', trans('general.product_deleted_from_collection'));
        }else{
            abort(403, 'Unauthorized');
        }
    }



}
