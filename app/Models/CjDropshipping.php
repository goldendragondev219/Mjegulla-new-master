<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CjDropshipping extends Model
{
    protected $table = 'cj_dropshipping_details';

    protected $fillable = [
        'user_id',
        'shop_id',
        'email',
        'api_key',
        'generated_token',
        'token_exp_date',
    ];
}
