<?php
namespace App\Http\Middleware;

use App\Models\Ip;
use App\Models\Manager;
use Closure;
use Illuminate\Support\Facades\Auth;

class AccessIP extends CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()){
            return redirect('/login');
        }

        $requestIP = $request->ip();

        if (!Manager::checkEnableIP() || ($this->isAdmin() && !Ip::getAddressIP($requestIP)) || ($requestIP && Ip::getAddressIP($requestIP))) {
            return $next($request);
        }

        $request->session()->flush();
        return redirect('/access-ip');

    }
}
