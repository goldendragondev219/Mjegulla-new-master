<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';
    protected $fillable = [
        'user_id',
        'shop_id',
        'product_id',
        'variant_id',
        'deleted',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

}

