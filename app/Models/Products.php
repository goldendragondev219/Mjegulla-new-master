<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    public function product_images()
    {
        return $this->hasMany(ProductImages::class)->where('deleted', 'no')->orderBy('id', 'DESC');
    }

    public function variants()
    {
        return $this->hasMany(Variants::class)->where('deleted', 'no');
    }

    public function collections()
    {
        return $this->belongsTo(CollectionProducts::class, 'product_id', 'id');
    }


    protected $fillable = [
        'user_id',
        'shop_id',
        'variant_id',
        'base_price',
        'base_price_discount',
        'product_sku',
        'product_name',
        'quantity',
        'product_category',
        'admin_menu_item_id',
        'shipping_rates',
        'product_description',
        'product_seo_keywords',
        'product_url',
        'product_single_image',
    ];



}