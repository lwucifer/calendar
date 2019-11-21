<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignOptionResource extends JsonResource
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
            'type' => $this->type,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'weekday_fee' => $this->weekday_fee,
            'holiday_fee' => $this->holiday_fee,
            'memo' => $this->memo,
            'weekday_benefits' => $this->weekday_benefits,
            'holiday_benefits' => $this->holiday_benefits,
            'campaign_id' => $this->campaign_id,
            'content' => $this->content,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
