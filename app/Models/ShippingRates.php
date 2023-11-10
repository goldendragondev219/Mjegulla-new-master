<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRates extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'country',
        'country_code',
        'price',
        'enabled',
    ];
}
