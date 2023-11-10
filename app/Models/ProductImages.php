<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{

    protected $fillable = [
        'user_id', 
        'shop_id', 
        'product_id', 
        'image_url', 
        'deleted'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
