<?php

namespace App\Exports;

use App\Models\Role;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\User;

class UsersExport implements FromView
{
    use Exportable;
    
    public function getUsers()
    {
        return User::where(User::getConditionManager([
            'is_deleted' => false
        ]))->where('role_id','<>', 1)->get();
    }

    public function view(): View
    {
        return view('exports.users', [
            'users' => $this->getUsers()
        ]);
    }
}
