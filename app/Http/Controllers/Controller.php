<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var $userInfo
     */
    protected $userInfo = null;

    protected function isLogin() {
        return Auth::check();
    }

    /**
     * @return mixed
     */
    public function getUserInfo()
    {
        $this->userInfo = $this->userInfo ? $this->userInfo : (new User())->getUserInfo(['id' => Auth::id(), 'is_deleted' => false]);

        return $this->userInfo;
    }

    /**
     * isAdmin Role
     */
    protected function isAdmin()
    {
        return Role::isAdmin($this->getUserInfo()['role']['name']);
    }

    /**
     * isManger Role
     */
    protected function isManger()
    {
        return Role::isManager($this->getUserInfo()['role']['name']);
    }
}
