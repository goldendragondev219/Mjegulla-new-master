<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketMessages extends Model
{
    protected $table = 'support_ticket_messages';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'admin_id',
        'message',
        'seen',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
