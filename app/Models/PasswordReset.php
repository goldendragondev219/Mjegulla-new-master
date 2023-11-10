<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PasswordReset extends Model
{
    protected $table = 'password_reset_code';

    protected $fillable = [
        'user_id',
        'page_key',
        'code',
        'tried'
    ];

}
