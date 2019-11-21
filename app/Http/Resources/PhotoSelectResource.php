<?php

namespace App\Http\Resources;

use App\Models\CashDepart;
use App\Models\PhotoOption;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoSelectResource extends JsonResource
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
            'name' => $this->name
        ];
    }
}
