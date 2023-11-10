<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Shop;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Cache;


class CategoryController extends Controller
{
    public function view()
    {
        if(auth()->user()->managing_shop){
            $shop_id = auth()->user()->managing_shop;
            $shop = Shop::find($shop_id);
            if($shop->user_id === auth()->user()->id){
                return view('categories', 
                [
                    'shop_id' => $shop_id,
                    'categories' => auth()->user()->shops()->find($shop_id)->categories($shop_id),
                    'shop_name' => $shop->shop_name,
                    'shop_url' => ($shop->custom_domain_activated) ? "https://{$shop->custom_domain}" : "https://{$shop->my_shop_url}." . parse_url(config('app.url'), PHP_URL_HOST),
                ]);
            }else{
                abort(401, 'Unauthorized');
            }
        }
    }


    public function create(Request $request, $id)
    {
        $data = $request->validate([
            'category_name' => 'required|string|min:4|max:30|regex:/^[a-zA-Z0-9\s\/\-]+$/',
            'category_url' => 'required|string|min:4|max:100|regex:/^[a-zA-Z0-9\-]+$/',
        ]);
        
        
        $create = Categories::create([
            'user_id' => auth()->user()->id,
            'shop_id' => $id,
            'category_name' => $request->category_name,
            'category_url' => $request->category_url,
        ]);

        if($create){
            return redirect()->back()->with('success', trans('general.category_created_successfully'));
        } else {
            return redirect()->back()->withErrors(trans('general.there_was_an_error'));
        }
        
    }


    public function delete($id)
    {
        $category = Categories::find($id);
        if($category->user_id === auth()->user()->id){
            $delete = $category->delete();
            if($delete){
                return redirect()->back()->with('success',trans('general.category_deleted_successfully'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }
        }
    }


    public function update(Request $request, $id)
    {

        $category = Categories::find($id);
        
        if($category->user_id === auth()->user()->id){
            $data = $request->validate([
                'category_name' => 'required|string|min:4|max:30|regex:/^[a-zA-Z0-9\s\/\-]+$/',
            ]);

            $category->category_name = $request->category_name;
            $update = $category->save();

            if($update){
                return redirect()->back()->with('success',trans('general.category_updated_successfully'));
            } else {
                return redirect()->back()->withErrors(trans('general.there_was_an_error'));
            }

        }
    }


}
