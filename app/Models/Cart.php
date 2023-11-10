<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = [
        'user_id',
        'shop_id',
        'product_id',
        'variant_id',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }


    public function variant()
    {
        return $this->belongsTo(Variants::class, 'product_id', 'product_id')
            ->where('variant_id', $this->variant_id);
    }
    


}
