<?php

namespace App\Http\Controllers;

use Harimayco\Menu\Controllers\MenuController as ControllersMenuController;
use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Str;

class MenuController extends ControllersMenuController
{
    public function createnewmenu($menu_name = null, $shop_id = null)
    {
        if(auth()->check() && auth()->user()->managing_shop){
            $menu = new Menus();
            $menu->name = $menu_name ?? request()->input("menuname");
            $menu->user_id = auth()->user()->id;
            $menu->shop_id = $shop_id ?? auth()->user()->manging_shop;
            $menu->save();
            return json_encode(array("resp" => $menu->id));
        }
    }

    public function deleteitemmenu()
    {
        if(auth()->check() && auth()->user()->managing_shop){
            MenuItems::where('id', request()->input("id"))
                ->where('user_id', auth()->user()->id)
                ->where('shop_id', auth()->user()->managing_shop)->delete();
        }
    }

    public function deletemenug()
    {
        $menuId = request()->input("id");
    
        $menu = Menus::where('user_id', auth()->user()->id)
                     ->where('shop_id', auth()->user()->managing_shop)
                     ->find($menuId);
    
        if (!$menu) {
            return json_encode(array("resp" => "Menu not found or you do not have permission to delete it", "error" => 1));
        }
    
        $menu->delete();
    
        return json_encode(array("resp" => "You deleted this menu item"));
    }
    

    public function updateitem()
    {
        $arraydata = request()->input("arraydata");
    
        if (is_array($arraydata)) {
            foreach ($arraydata as $value) {
                $menuitem = MenuItems::where('id', $value['id'])
                    ->where('user_id', auth()->user()->id)
                    ->where('shop_id', auth()->user()->managing_shop)
                    ->first();
    
                if (!$menuitem) {
                    abort(403, 'Unauthorized');
                }
    
                $menuitem->label = $value['label'];
                $menuitem->link = $value['link'];
                //$menuitem->class = $value['class'];
    
                if (config('menu.use_roles')) {
                    $menuitem->role_id = $value['role_id'] ? $value['role_id'] : 0;
                }
    
                $menuitem->save();
            }
        } else {
            $menuitem = MenuItems::where('id', request()->input("id"))
                ->where('user_id', auth()->user()->id)
                ->where('shop_id', auth()->user()->managing_shop)
                ->first();
    
            if (!$menuitem) {
                return json_encode(array("resp" => "Menu item not found or you do not have permission to update it", "error" => 1));
            }
    
            $menuitem->label = request()->input("label");
            $menuitem->link = Str::slug(request()->input("label"));
            //$menuitem->class = request()->input("clases");
    
            if (config('menu.use_roles')) {
                $menuitem->role_id = request()->input("role_id") ? request()->input("role_id") : 0;
            }
    
            $menuitem->save();
        }
    }
    

    public function addcustommenu()
    {
        if (request()->input("labelmenu") == null) {
            return back()->with('error', trans('general.label_not_set'));
        }
             
        $menuitem = new MenuItems();
        $menuitem->label = request()->input("labelmenu");
        $menuitem->link = Str::slug(request()->input("labelmenu"));

        
        if (config('menu.use_roles')) {
            $menuitem->role_id = request()->input("rolemenu") ? request()->input("rolemenu") : 0;
        }
        
        $menuitem->menu = request()->input("idmenu");
        $menuitem->sort = MenuItems::getNextSortRoot(request()->input("idmenu"));
        
        // Set user_id and shop_id based on the authenticated user.
        $menuitem->user_id = auth()->user()->id;
        $menuitem->shop_id = auth()->user()->managing_shop;
        
        $menuitem->save();
    }
    

    public function generatemenucontrol()
    {
        $menu = Menus::where('id', request()->input("idmenu"))
            ->where('user_id', auth()->user()->id)
            ->where('shop_id', auth()->user()->managing_shop)
            ->first();
    
        if (!$menu) {
            return json_encode(array("resp" => "Menu not found or you do not have permission to update it", "error" => 1));
        }
    
        $menu->name = request()->input("menuname");
        $menu->save();
    
        if (is_array(request()->input("arraydata"))) {
            foreach (request()->input("arraydata") as $value) {
                $menuitem = MenuItems::where('id', $value["id"])
                    ->where('user_id', auth()->user()->id)
                    ->where('shop_id', auth()->user()->managing_shop)
                    ->first();
    
                if (!$menuitem) {
                    abort(403, 'Unauthorized');
                }
    
                $menuitem->parent = $value["parent"];
                $menuitem->sort = $value["sort"];
                $menuitem->depth = $value["depth"];
                
                if (config('menu.use_roles')) {
                    $menuitem->role_id = request()->input("role_id");
                }
                
                $menuitem->save();
            }
        }
    
        return json_encode(array("resp" => 1));
    }
    
}