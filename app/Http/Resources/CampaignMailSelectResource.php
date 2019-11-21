<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignMailSelectResource extends JsonResource
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
            'send_time' => $this->send_time,
            'day' => $this->day,
            'type' => $this->type,
            'template' => $this->template,
            'action' => $this->action,
            'campaign_id' => $this->campaign_i
        ];
    }
}
