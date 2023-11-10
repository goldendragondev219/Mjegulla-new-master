<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $table = 'help';

    protected $fillable = [
        'belongs_to_menu',
        'title',
        'content',
        'menu',
        'lang',
        'slug',
    ];



    public static function subMenus($menu_id)
    {
        return Help::where('belongs_to_menu', $menu_id)->get();
    }


    public function belongsToMenu()
    {
        return $this->belongsTo(Help::class, 'belongs_to_menu', 'id')->select('title');
    }
    

}
