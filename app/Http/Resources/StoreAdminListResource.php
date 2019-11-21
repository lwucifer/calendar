<?php

namespace App\Http\Resources;

use App\Models\Lane;
use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class StoreAdminListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'manager' => UserRelationSelectResource::collection(DB::table('users')
                ->where('id','=',$this->manager_id)
                ->get()),
            'manager_id' => $this->manager_id,
            'is_enable' => $this->is_enable,
            'phone' => $this->phone,
            'last_update_by' => UserRelationSelectResource::collection(DB::table('users')
                ->where('id','=',$this->last_update_by)
                ->get()),
            'updated_at' => $this->updated_at,
        ];
    }
}
