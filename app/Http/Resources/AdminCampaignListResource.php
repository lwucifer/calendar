<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class AdminCampaignListResource extends JsonResource
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
            'photo' => new PhotoRelationResource($this->photo),
            'store' => StoreSelectResource::collection(DB::table('store')
                ->join('campaign_stores', 'store.id', '=', 'campaign_stores.store_id')
                ->where('campaign_stores.campaign_id','=',$this->id)
                ->where('campaign_stores.is_deleted','=',false)
                ->select('store.*')
                ->get()),
            'web_name' => $this->web_name,
            'time' => $this->time,
            'photo_id' => $this->photo_id,
            'is_display_calendar' => $this->is_display_calendar,
            'is_enable' => $this->is_enable,
            'setting_code' => $this->setting_code,
            'is_deleted' => $this->is_deleted,
            'last_update_by' => UserRelationSelectResource::collection(DB::table('users')
                ->where('id','=',$this->last_update_by)
                ->get()),
            'updated_at' => $this->updated_at
        ];
    }
}
