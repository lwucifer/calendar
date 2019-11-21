<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
	/**
	 * @var $role
	 */
	protected $role;
	
	/**
	 * @return mixed
	 */
	protected function getRoleInfo()
	{
		if (!Auth::check()) {
			return $this->redirectDefault();
		}
		
		try {
			return $this->role = Role::getRoleInfoWithRoleId(Auth::user()->role_id);
		} catch (\Exception $e) {
			return $this->redirectDefault();
		}
	}
	
	/**
	 * @param string $path
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	protected function redirectDefault($path = '/login')
	{
		return redirect($path);
	}
	
	/**
	 * unauthorized
	 * @param int $status
	 */
	protected function unauthorized($status = 401)
	{
		return abort($status);
	}
	
	/**
	 * @param $roleName
	 * @return bool
	 */
	protected function isRole($roleName)
	{
		return $roleName === $this->getRoleInfo()['name'];
	}
	
	/**
	 * @return bool
	 */
	protected function isManager()
	{
		return $this->isRole(Role::ROLE_NAME['manager']);
	}
	
	/**
	 * @return bool
	 */
	protected function isAdmin()
	{
		return $this->isRole(Role::ROLE_NAME['admin']);
	}
	
	/**
	 * @return bool
	 */
	protected function isUser()
	{
		return $this->isRole(Role::ROLE_NAME['user']);
	}
	
	/**
	 * @return bool
	 */
	protected function isCustomer()
	{
		return $this->isRole(Role::ROLE_NAME['customer']);
	}
}
