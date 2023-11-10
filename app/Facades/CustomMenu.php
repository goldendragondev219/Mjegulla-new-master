<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class CustomMenu extends Facade {
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'harimayco-menu-custom';
    }
}
