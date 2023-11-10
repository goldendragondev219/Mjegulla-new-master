<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPackages extends Model
{
    protected $fillable = [
        'shop_id', 
        'package_type', 
        'details', 
        'amount', 
        'ends_at', 
        'will_cancel',
        'sub_id',
    ];
}
