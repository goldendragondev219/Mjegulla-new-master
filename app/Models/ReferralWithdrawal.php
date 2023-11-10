<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralWithdrawal extends Model
{
    protected $table = 'referral_withdrawals';

    protected $fillable = [
        'user_id',
        'transfer_details',
        'amount',
        'status',
    ];
}
