<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collections extends Model
{
    protected $table = 'collections';

    protected $fillable = [
        'name',
        'user_id',
        'shop_id',
    ];


    public function products()
    {
        return $this->hasMany(CollectionProducts::class, 'collection_id', 'id')->with('product');
    }

}
