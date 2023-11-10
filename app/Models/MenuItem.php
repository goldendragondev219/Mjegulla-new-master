<?php

namespace App\Models;

use Harimayco\Menu\Models\MenuItems;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends MenuItems
{
    public function subCategories()
    {
        return $this->hasMany('Harimayco\Menu\Models\MenuItems','parent');
    }
}