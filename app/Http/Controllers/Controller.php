<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Shop;
use App\Models\SubscriptionPackages;
use App\Models\Notifications;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    //increment available balance to shops
    public function add_balance_to($shop_id, $amount, $type_of_payment)
    {
        if($type_of_payment !== 'cash'){
            $package = SubscriptionPackages::where('shop_id', $shop_id)->first();
            if($package){
                $type = $package->package_type;
                if($type == 'Basic'){
                    $amount = $amount - ($amount * 8 / 100) - 0.50;
                }else{
                    $amount = $amount - ($amount * 5 / 100) - 0.50;
                }
                Shop::where('id', $shop_id)->increment('balance_available', $amount);
            }else{
                //free
                $amount = $amount - ($amount * 10 / 100) - 0.50;
                Shop::where('id', $shop_id)->increment('balance_available', $amount);
            }
        }else{
            //user has used cash don't add it to balance_available.
        }
    }


    public function sendNotification($user_id, $shop_id, $notif_id, $amount = null)
    {
        Notifications::create([
            'user_id' => $user_id,
            'shop_id' => $shop_id,
            'notif_id' => $notif_id,
            'amount' => $amount,
        ]);
    }
    

    public function markNotificationsAsRead()
    {
        Notifications::where('user_id', auth()->user()->id)->update(['seen' => 'yes']);
    }
    

}
