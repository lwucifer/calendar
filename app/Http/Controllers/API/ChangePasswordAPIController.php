<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Requests\PasswordExpiredRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Validator;
use Illuminate\Http\Request;


class ChangePasswordAPIController extends BaseController
{
    protected $modal = 'App\Models\User';
    protected $resource = '';
    private $collection = '';
    public function changePassword(Request $request){
        $re = Helper::getRequestJson($request);
        $message = [
            'password.regex' => Lang::get('passwords.change_password_regex'),
            'password.min' => Lang::get('passwords.change_password_regex'),
            'password.different' => Lang::get('passwords.change_password_different'),
        ];
        $validator = Validator::make($re, (new $this->modal)->fieldSetValidateChangePass(),$message);

        if ($validator->fails()) {
            return $this->sendError(404, $validator->errors());
        }

        $id = Auth::id();
        $user = User::find($id);
        // Checking current password
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError(404, ['current_password' => Lang::get('passwords.change_password_wrong_current_pass')]);
        }

        if ($request->password == $user->username){
            return $this->sendError(404, ['password' => Lang::get('passwords.change_password_same_userid')]);
        }

        $user->password = bcrypt($request->password);
        $user->updated_password_at = Carbon::now()->toDateTimeString();
        $user->save();
        return $this->sendResponse(['status' => Lang::get('passwords.change_password_success')],200);
    }
}
