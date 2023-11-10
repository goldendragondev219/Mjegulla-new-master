<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;
use App\Models\PasswordReset;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function genCode(Request $request)
    {
        $email = $request->email;

        $user = User::where('email', $email)->first();
    
        if (!$user) {
            return redirect('password/reset');
        }
    
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $page_key = Str::random(100);
    
        PasswordReset::updateOrCreate(
            ['user_id' => $user->id],
            [
                'page_key' => $page_key,
                'code' => $code,
            ]
        );
        $mail_data = [
            'lang' => $user->default_language,
            'code' => $code,
        ];
        Mail::to($user->email)->send(new ResetPasswordCode($mail_data));
        return redirect()->route('password_confirm_view', $page_key);
    }


    public function update(Request $request)
    {
        $data = $request->validate([
            'password' => 'required|string|min:8|max:50|confirmed',
            'code' => 'required',
        ]);

        $code = $request->code;
        $page_key = $request->page_key;
        $confirm = PasswordReset::where('code', $code)->where('page_key', $page_key)->first();
        
        if($confirm){
            $user = User::find($confirm->user_id);
            $user->update([
                'password' => $password = Hash::make($request->password)
            ]);
            $confirm->delete();
            return redirect('/login')->with('success', trans('general.password_updated_successfully'));
        }else{
            $tried = PasswordReset::where('page_key', $page_key)->first();
            if ($tried) {
                $tried->increment('tried', 1);
                if ($tried->tried >= 3) {
                    $tried->delete();
                    return redirect('password/reset');
                }
            }
            return redirect()->back()->with('error', trans('general.provided_code_incorrect', ['tries' => 3-$tried->tried]));
        }  
    }


    public function confirmView($page_key)
    {
        $reset = PasswordReset::where('page_key', $page_key)->first();
        if(!$reset){
            abort(404);
        }
        return view('auth.passwords.confirm', [
            'page_key' => $page_key,
        ]);
    }
    


}
