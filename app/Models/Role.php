<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	/**
	 * Role defined
	 */
	const ROLE_NAME = [
		'admin' => 'admin',
		'manager' => 'manager',
		'user' => 'user',
		'customer' => 'customer'
	];
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';
	
	/**
	 * One to Many relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function users()
	{
		return $this->hasMany('App\Models\User');
	}
	
	/**
	 * @param $condition
	 * @return mixed
	 */
	public static function getRole($condition = [])
	{
		return Role::where($condition)->first();
	}
	
	/**
	 * @param $roleId
	 * @return mixed
	 */
	public static function getRoleInfoWithRoleId($roleId)
	{
		return self::getRole(['id' => $roleId]);
	}
	
	/**
	 * @param $roleName
	 * @return bool
	 */
	public static function isManager($roleName)
	{
		return Role::ROLE_NAME['manager'] === $roleName;
	}
	
	/**
	 * @param $roleName
	 * @return bool
	 */
	public static function isAdmin($roleName)
	{
		return Role::ROLE_NAME['admin'] === $roleName;
	}
	
	/**
	 * @param $roleName
	 * @return bool
	 */
	public static function isUser($roleName)
	{
		return Role::ROLE_NAME['user'] === $roleName;
	}
	
	/**
	 * @param $roleName
	 * @return bool
	 */
	public static function isCustomer($roleName)
	{
		return Role::ROLE_NAME['customer'] === $roleName;
	}
	
}