<?php

namespace App\Http\Controllers\API;

use App\Models\Role;

/**
 * Class RoleController
 * @package App\Http\Controllers\API
 */
class RoleController extends BaseController
{
	/** Overwrite this modal
	 *
	 * @var string
	 */
	protected $modal = 'App\Models\Role';
	
	/** Overwrite this resource
	 * @var string
	 */
	protected $resource = 'App\Http\Resources\RoleResource';
	
	/**
	 * Get info role of user logging
	 *
	 * @return array
	 */
	public function userRole()
	{
		return $this->sendResponse($this->getUserInfo(), 200);
	}
	
	/**
	 * Get role config defined
	 *
	 * @return array
	 */
	public function getRoleName()
	{
		return $this->sendResponse(Role::ROLE_NAME, 200);
	}
	
}
