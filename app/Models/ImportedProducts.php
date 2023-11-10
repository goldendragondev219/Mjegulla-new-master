<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedProducts extends Model
{
    protected $table = 'imported_products';

    protected $fillable = [
        'user_id',
        'shop_id',
        'product_id',  
    ];
}
