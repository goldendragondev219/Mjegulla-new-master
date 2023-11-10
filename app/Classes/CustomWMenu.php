<?php

namespace App\Classes;

use Harimayco\Menu\Models\Menus;
use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\WMenu;
use Illuminate\Support\Facades\DB;

class CustomWMenu extends WMenu
{

    public function render()
    {
        $menu = new Menus();
        $menuitems = new MenuItems();
        $menulist = $menu->select(['id', 'name'])->get();
        $menulist = $menulist->pluck('name', 'id')->prepend('Select menu', 0)->all();

        $menu = Menus::where('user_id', auth()->id())->where('shop_id',auth()->user()->managing_shop)->first();
        $menus = $menuitems->where('menu',$menu->id)->get();

        $data = ['menus' => $menus, 'indmenu' => $menu, 'menulist' => $menulist];
        if (config('menu.use_roles')) {
            $data['roles'] = DB::table(config('menu.roles_table'))->select([config('menu.roles_pk'), config('menu.roles_title_field')])->get();
            $data['role_pk'] = config('menu.roles_pk');
            $data['role_title_field'] = config('menu.roles_title_field');
        }
        return view('wmenu::menu-html', $data);
    }
}
