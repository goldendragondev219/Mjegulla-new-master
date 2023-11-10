<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variants extends Model
{
    protected $fillable = [
        'variant_id',
        'user_id',
        'shop_id',
        'product_sku',
        'product_id',
        'size',
        'price',
        'quantity',
        'quantity_left',
        'color',
        'material',
        'deleted',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id')->where('deleted', 'no');
    }
    
    

}
