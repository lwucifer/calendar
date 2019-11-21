<?php

namespace App\Http\Controllers\API;

use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard()
    {
        $data = array(
            'bookRequestYesterday' => $this->bookRequestYesterday(),
            'cancelBookYesterday' => $this->cancelBookYesterday(),
            'totalNewUserInMonth' => $this->totalNewUserInMonth()
        );

        return $this->sendResponse($data, 200);
    }

    protected function bookRequestYesterday()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        if ($this->isAdmin()) {
            return Plan::where('status', Plan::STATUS_NEW)
                ->whereDate('created_at',$yesterday)
                ->count();
        }
        $storeIdOfUser = $this->getUserInfo()->store_id;
        return Plan::where('status', Plan::STATUS_NEW)
            ->where('store_id', $storeIdOfUser)
            ->whereDate('created_at',$yesterday)
            ->count();
    }

    protected function cancelBookYesterday()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        if ($this->isAdmin()) {
            return Plan::where('status', Plan::STATUS_CANCEL)
                ->whereDate('created_at',$yesterday)
                ->count();
        }
        $storeIdOfUser = $this->getUserInfo()->store_id;
        return Plan::where('status', Plan::STATUS_CANCEL)
            ->where('store_id', $storeIdOfUser)
            ->whereDate('created_at',$yesterday)
            ->count();
    }

    protected function totalNewUserInMonth()
    {
        $month = Carbon::now()->month;
        if ($this->isAdmin()) {
            return User::join('roles', 'roles.id', '=', 'users.role_id')
                ->where('roles.name', Role::ROLE_NAME['customer'])
                ->where('users.is_deleted', false)
                ->whereMonth('users.created_at',$month)
                ->count();
        }
        $storeIdOfUser = $this->getUserInfo()->store_id;
        return User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->where('roles.name', Role::ROLE_NAME['user'])
            ->where('users.is_deleted', false)
            ->where('users.store_id', $storeIdOfUser)
            ->whereMonth('users.created_at',$month)
            ->count();

    }


}
