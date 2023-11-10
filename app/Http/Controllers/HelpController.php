<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Help;

class HelpController extends Controller
{
    public function index($slug = null)
    {
        $user_lang = session()->get('locale') ?? 'sq';
        if($slug){
            $data = Help::where('slug', $slug)->firstOrFail();
        }else{
            $data = Help::where('lang', $user_lang)->where('menu', 'no')->first();
        }
        
        $side_menu = Help::where('lang', $user_lang)->where('menu', 'yes')->get();

        return view('help_center.help_center',[
            'data' => $data,
            'side_menu' => $side_menu,
        ]);
    }
}
