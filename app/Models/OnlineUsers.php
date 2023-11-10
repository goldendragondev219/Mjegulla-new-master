<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineUsers extends Model
{
    protected $table = 'online_users';
    protected $fillable = [
        'user_id',
        'shop_id',
        'referer',
        'online_at',
        'ip',
    ];
}
