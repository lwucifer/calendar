<?php

namespace App\Http\Controllers;
use App\Http\Requests\UnlockAccountRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
class UnlockAccountController extends Controller
{
    public function unlockAccount()
    {
        return view('auth.unlock');
    }
    public function postUnlockAccount(UnlockAccountRequest $re){
        $user = User::where('username',$re->username)
            ->where('email',$re->email)
            ->where('is_deleted',false);
        if ($user->count() == 0){
            return redirect()->back()->withErrors(['status'=> Lang::get('passwords.unlock_error')]);
        }
        $data = [
            'username' => $re->username,
            'email' => $re->email
        ];
        Mail::send('auth.email.unlock',$data,function ($msg){
            $msg->to(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
            $msg->subject(Lang::get('passwords.unlock_success'));
        });
        return redirect()->back()->with(['success' => Lang::get('passwords.unlock_success') ]);
    }
}
