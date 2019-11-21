<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class PasswordExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $defaultSettingTimeExpiredPassword = 180;

    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (!$user){
            $request->session()->flush();
            return redirect('/login');
        }

        $updated_password_at = new Carbon(($user->updated_password_at) ? $user->updated_password_at : $user->created_at);
        $settingDaysExpired = $user->setting_expire_password ? $user->setting_expire_password : $this->defaultSettingTimeExpiredPassword ;

        if (Carbon::now()->diffInDays($updated_password_at) >= $settingDaysExpired) {
            return redirect()->route('password.expired');
        }

        return $next($request);
    }
}
