<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LockUser;
use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $attempts = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if (!$this->isExistEmail($request)) {
            return $this->sendFailedLoginResponse($request);
        }

        if ($this->checkLockUser($request)) {
            return $this->sendLockoutResponse($request);
        }
        $numberLoginFail = $this->getNumberLoginFail($request) + 1;

        if ($this->hasTooManyLoginAttempts($request)) {
            $locked = $this->lockUser($request);
            if ($locked) {
                $this->completeLoginLog($request);
            }
            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {
            $this->completeLoginLog($request);
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request, $numberLoginFail);
    }

    protected function attemptLogin(Request $request)
    {
        $data = $this->credentials($request);
        $data['is_deleted'] = false;

        return $this->guard()->attempt(
            $data , $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password','is_deleted');
    }

    protected function sendFailedLoginResponse(Request $request, $numberLoginFail = null)
    {
        $msg = [
            $this->username() => [trans('auth.failed')],
        ];

        if ($numberLoginFail > 3) {
            $msg['timeToLock'] = [trans('auth.time_to_lock', ['number' => $numberLoginFail, 'total' => $this->attempts])];
        }

        throw ValidationException::withMessages($msg);
    }

    public function sendLockoutResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'locked' => [Lang::get('auth.user_lock', ['name' => $request->email])],
        ])->status(429);
    }

    protected function incrementLoginAttempts(Request $request)
    {
        $this->writeLogLogin($request);
    }

    protected function isExistEmail(Request $request)
    {
        return User::where('email', $request->email)->where('is_deleted', false)->count() > 0;
    }

    protected function completeLoginLog(Request $request)
    {
        $log = Log::where('title', Log::LOGIN_TITLE)->where('email', $request->email)->where('status', Log::FAIL_STATUS);

       if ($log->count() > 0) {
            $log->update(['status' => Log::COMPLETE_STATUS]);
       }
    }

    protected function checkLockUser(Request $request)
    {
        $check = LockUser::leftJoin('users', function ($join) {
            $join->on('lock_users.user_id', "=", 'users.id');
        })->where('users.email', $request->email)->where('is_locked', true)->count();

        return $check > 0;
    }

    protected function lockUser(Request $re)
    {
        $user = User::where('email', $re->email)->first();
        if ($user->count() == 0) {
            return false;
        }
        $lockUser = new LockUser();
        $lockUser->user_id = $user->id;
        $lockUser->type = LockUser::TYPE_LOGIN_FAIL;
        $lockUser->is_locked = true;
        $lockUser->save();
        return $lockUser->user_id;
    }

    protected function writeLogLogin(Request $re)
    {
        $log = new Log();
        $log->user_id = '';
        $log->email = $re->email;
        $log->title = Log::LOGIN_TITLE;
        $log->note = '';
        $log->status = Log::FAIL_STATUS;
        $log->save();
    }

    protected function getNumberLoginFail(Request $request)
    {
        return Log::numberLoginFail($request->email);
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        $numberLogFail = Log::numberLoginFail($request->email);
        return $numberLogFail >= $this->attempts;
    }
}
