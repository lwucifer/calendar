<?php

namespace App\Http\Resources;

use App\Models\CashDepart;
use App\Models\PhotoOption;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoAdminListResource extends JsonResource
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
            'option' => PhotoOptionResource::collection(PhotoOption::where('photo_id', $this->id)->get()),
            'is_enable' => $this->is_enable,
            'cash_id' => CashResource::collection(CashDepart::where('id',$this->cash_id)->get()),
            'comment' => $this->comment,
            'last_update_by' => UserRelationSelectResource::collection(User::where('id', $this->last_update_by)->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
