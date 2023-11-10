<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShopReActivationNotification;

use App\Models\Shop;
use App\Models\User;


class ReActivateShops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reactivate:shops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reactivate deactivated shops, at the beginning of the month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shops = Shop::where('active', 'no')->get();

        foreach ($shops as $shop) {
            $shop->active = 'yes';
            $shop->save();

            $user = User::find($shop->user_id);
            $data = [
                'name' => $user->name,
                'lang' => $user->default_language,
                'store_name' => $shop->shop_name,
            ];
            Mail::to($user->email)->queue(new ShopReActivationNotification($data));

        }
    }
}
