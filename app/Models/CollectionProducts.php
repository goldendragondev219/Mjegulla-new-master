<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionProducts extends Model
{
    protected $table = 'collection_products';

    protected $fillable = [
        'collection_id',
        'user_id',
        'shop_id',
        'product_id',
    ];


    public function collection()
    {
        return $this->belongsTo(Collections::class, 'collection_id', 'id');
    }

    public function product()
    {
        return $this->hasMany(Products::class, 'id', 'product_id');
    }

}
