<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{
    protected $table = 'withdrawals';

    protected $fillable = [
        'user_id',
        'shop_id',
        'transfer_details',
        'amount',
        'status',
    ];
}
