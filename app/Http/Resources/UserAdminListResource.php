<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FNumber;
use DB;

class UserAdminListResource extends JsonResource
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
			'store' => new StoreSelectResource($this->store),
            'role' => new RoleResource($this->role),
			'f_number' => FNumberResource::collection(
				FNumber::where('user_id', $this->id)->orderBy('updated_at', 'desc')->get()
			),
            'username' => $this->username,
			'is_enable' => $this->is_enable,
			'use_flag' => $this->use_flag,
			'email' => $this->email,
            'comment' => $this->comment,
            'last_update_by' => UserRelationResource::collection(DB::table('users')
                ->where('id','=',$this->last_update_by)
                ->get()),
			'updated_at' => $this->updated_at,
		];
	}
}
