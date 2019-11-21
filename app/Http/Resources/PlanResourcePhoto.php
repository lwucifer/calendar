<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use App\Models\Photo;
use Illuminate\Http\Resources\Json\JsonResource;
use DB;
class PlanResourcePhoto extends JsonResource
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
            'store' => new StoreRelationResource($this->store),
            'photo' => new PhotoResource(Photo::find(Campaign::where('id', $this->campaign->id)->first()->photo_id)),
            'option' => PlanOptionResource::collection(DB::table('plan_campaign_option')
                ->where('plan_id', '=', $this->id)
                ->where('is_deleted','=',false)
                ->get()),
            'campaign' => $this->campaign,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'user_name' => $this->user_name,
            'user_phone' => $this->user_phone,
            'user_email' => $this->user_email,
            'comment' => $this->comment,
            'is_enable' => $this->is_enable,
            'is_deleted' => $this->is_deleted,
            'data_customer' => $this->data_customer,
            'last_update_by' => UserRelationResource::collection(DB::table('users')
                ->where('id','=',$this->last_update_by)
                ->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
