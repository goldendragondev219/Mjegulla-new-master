<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTickets extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'ticket_id',
        'title',
        'user_id',
        'updated_at',
        'user_seen',
        'admin_seen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
