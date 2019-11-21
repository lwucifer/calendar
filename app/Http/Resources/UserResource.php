<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FNumber;
use App\Models\User;

class UserResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'store' => new StoreResource($this->store),
			'role' => new RoleResource($this->role),
			'f_number' => FNumberResource::collection(
				FNumber::where('user_id', $this->id)->orderBy('updated_at', 'desc')->get()
			),
			'is_enable' => $this->is_enable,
			'use_flag' => $this->use_flag,
			'username' => $this->username,
			'email' => $this->email,
			'password' => $this->password,
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'kana_first_name' => $this->kana_first_name,
			'kana_last_name' => $this->kana_last_name,
			'phone' => $this->phone,
			'zip_code' => $this->zip_code,
			'address1' => $this->address1,
			'address2' => $this->address2,
			'other1' => $this->other1,
			'other2' => $this->other2,
			'comment' => $this->comment,
			'parent_user' => $this->parent_user,
			'remember_token' => $this->remember_token,
			'last_update_by' => User::where('id', '=', $this->last_update_by)->get(),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
