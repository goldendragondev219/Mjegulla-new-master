<?php

namespace App\Models;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Orders;
use App\Models\OnlineUsers;
use App\Models\CjDropshipping;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    public function shops()
    {
        return $this->hasMany(Shop::class)->orderBy('id', 'DESC');
    }


    public function products($shop_id) {
        return $this->product()->where('shop_id', $shop_id)->where('deleted', 'no')->orderBy('id', 'DESC')->get();
    }
    
    

    public function product() {
        return $this->hasMany(Products::class)->where('deleted', 'no');
    }
    

    public function orders()
    {
        return Orders::where(['shop_id' => auth()->user()->managing_shop, 'finished' => 'yes']);
    }
    

    public function products_available()
    {
        return $this->shops()->where('id', auth()->user()->managing_shop)->value('products_available');
    }
    
    public function total_products()
    {
        return $this->shops()->where('id', auth()->user()->managing_shop)->value('total_products');
    }

    //unpaid cj dropshipping orders
    public function unshippedOrdersCount()
    {
       return $this->orders()->where(['cj_paid' => 'no', 'finished' => 'yes'])->count();
    }
    


    public function balanceAvailable()
    {
        return $this->shops()->where('id', auth()->user()->managing_shop)->value('balance_available');
    }

    
    public function earningsThisMonth()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        return $this->orders()
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');
    }


    public function earningsLast30Days()
    {
        $startOfPeriod = now()->subDays(29)->startOfDay();
        $endOfPeriod = now()->endOfDay();
    
        $earningsData = [];
        $labels = [];
    
        $orders = $this->orders()
            ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
            ->orderBy('created_at')
            ->get();
    
        $date = $startOfPeriod;
        for ($i = 0; $i < 30; $i++) {
            $label = $date->format('j M');
            $labels[] = $label;
    
            $earnings = 0;
            foreach ($orders as $order) {
                if ($order->created_at->format('Y-m-d') === $date->format('Y-m-d')) {
                    $earnings += $order->amount;
                }
            }
            $earningsData[] = $earnings;
    
            $date = $date->addDay();
        }
    
        return [
            'earningsData' => $earningsData,
            'labels' => $labels,
        ];
    }
    
    
    public function onlineUsers()
    {
        return OnlineUsers::where('shop_id', auth()->user()->managing_shop)
        ->whereBetween('online_at', [now(), now()->addMinutes(2)])
        ->count();
    }
    

    //get dashboard notifications count
    public function notificationsCount()
    {
        $notifications = Notifications::where(['user_id' => auth()->id(), 'seen' => 'no'])->count();
        if($notifications >= 99){
            $notifications = '99+';
        }
        return $notifications;
    }

    //get dashboard notificaitions
    public function getNotifications()
    {
        return Notifications::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->limit(5)->get();
    }



    //check if cj dropshipping is active
    public function active_cj_d()
    {
        $cj = CjDropshipping::where('user_id', auth()->user()->id)->where('shop_id', auth()->user()->managing_shop)->count();
        if($cj){
            return true;
        }else{
            return false;
        }
    }


    //managing store type
    public function isDropshipping()
    {
        if(auth()->user()->managing_shop){
            $drop = Shop::where('id', auth()->user()->managing_shop)->where('store_type', 'dropshipping')->count();
            if($drop){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'email_verified_at', 
        'customer_id', 
        'default_payment_method', 
        'default_language', 
        'managing_shop',
        'referral_balance',
        'last_seen',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
