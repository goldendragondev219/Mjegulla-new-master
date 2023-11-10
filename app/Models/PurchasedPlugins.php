<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedPlugins extends Model
{
    protected $fillable = [
        'user_id', 
        'shop_id', 
        'plugin_id', 
        'name', 
        'enabled'
    ];
}
