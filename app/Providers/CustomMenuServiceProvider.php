<?php

namespace App\Providers;

use App\Classes\CustomWMenu;
use Illuminate\Support\ServiceProvider;

class CustomMenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('harimayco-menu-custom', function () {
            return new CustomWMenu();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
