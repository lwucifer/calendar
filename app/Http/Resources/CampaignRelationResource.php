<?php

namespace App\Http\Resources;
use App\Models\CampaignOption;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignRelationResource extends JsonResource
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
            'option' => CampaignOptionResource::collection(CampaignOption::where('campaign_id', $this->id)->get()),
        ];
    }
}
