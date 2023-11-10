<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBilling extends Model
{
    protected $table = 'customer_billing';
    protected $fillable = [
        'user_id',
        'shop_id',
        'first_name',
        'last_name',
        'company',
        'country',
        'address',
        'city',
        'zip',
        'phone',
        'order_note',
        'payment_method',
    ];
}
