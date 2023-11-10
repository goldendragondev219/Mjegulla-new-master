<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreImpressions extends Model
{
    protected $table = 'store_impressions';

    protected $fillable = [
        'store_id',
        'user_location',
        'url',
        'user_ip',
        'referred',
        'user_agent',
    ];
}
