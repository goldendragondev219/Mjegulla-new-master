<?php

namespace App\Models;

use Harimayco\Menu\Models\Menus;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_description',
        'shop_seo_keywords',
        'my_shop_url',
        'shop_image_url',
        'home_featured_details',
        'header_message',
        'social_networks',
        'stripe_session',
        'products_available',
        'total_products',
        'balance_available',
        'payment_methods',
        'full_name',
        'company',
        'city',
        'address',
        'phone',
        'custom_domain',
        'custom_domain_activated',
        'active',
        'custom_css',
        'store_type',
        'theme',
        'google_analytics',
        'facebook_pixel',
        'tiktok_pixel',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function orders()
    {
        return $this->hasMany(Orders::class)->where('finished', 'yes')->orderBy('id', 'DESC');
    }

    public function products()
    {
        return $this->hasMany(Products::class);
    }


    public function images($product_id) {
        return $this->productImages()->where('product_id', $product_id)->orderBy('id', 'DESC')->get();
    }


    public function productImages() {
        return $this->hasMany(ProductImages::class);
    }


    public function categories($shop_id){
        return $this->getCategories()->where('shop_id', $shop_id)->orderBy('id', 'ASC')->where('parent', 0)->with('subCategories')->get();
    }

    public function getCategories() {
        return $this->hasMany(MenuItem::class);
    }


    public function variants($shop_id){
        return $this->getVariants()->where('shop_id', $shop_id)->orderBy('id', 'DESC')->get();
    }

    public function getVariants(){
        return $this->hasMany(Variants::class);
    }

    public function menus(){
        return $this->hasMany(Menus::class);
    }

}