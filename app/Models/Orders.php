<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'shop_id',
        'variant_id',
        'amount',
        'billing_id',
        'stripe_payment_id',
    ];


    public function store()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }


}
