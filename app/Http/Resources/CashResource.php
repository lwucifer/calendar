<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "name"          => $this->name,
            "manager_id"    => $this->manager_id,
            "is_deleted"    => $this->is_deleted,
            "created_at"    => $this->created_at,
            "updated_at"    => $this->updated_at
        ];
    }
}
