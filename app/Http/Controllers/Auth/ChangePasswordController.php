<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordExpiredRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class ChangePasswordController extends Controller
{
    public function changePassword(){
        return view('auth.passwords.change');
    }

    public function postChangePassword(PasswordExpiredRequest $request){
        $id = Auth::id();
        $user = User::find($id);
        // Checking current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => Lang::get('passwords.change_password_error_info')]);
        }

        if ($request->password == $user->username){
            return redirect()->back()->withErrors(['password' => Lang::get('passwords.change_password_error_same_id')]);
        }

        //update new password
        $user->password = bcrypt($request->password);
        $user->updated_password_at = Carbon::now()->toDateTimeString();
        $user->save();

        return redirect()->back()->with(['status' => Lang::get('passwords.change_password_success')]);
    }
}
