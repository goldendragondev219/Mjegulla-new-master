<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutMethods extends Model
{
    protected $table = 'payout_methods';

    protected $fillable = [
        'user_id',
        'shop_id',
        'details',
        'active',
    ];
}
