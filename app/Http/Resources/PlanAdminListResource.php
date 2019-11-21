<?php

namespace App\Http\Resources;

use App\Models\Plan;
use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class PlanAdminListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cus = $this->data_customer;
        $fid = '';
        if($cus) {
            foreach (json_decode($cus, true) as $key => $item) {
                if ($key == 'fid') {
                    $fid = $item;
                }
            }
        }
        return [
            'id' => $this->id,
            'store' => new StoreRelationResource($this->store),
            'option' => PlanOptionResource::collection(DB::table('plan_campaign_option')
                ->where('plan_id', '=', $this->id)
                ->where('is_deleted','=',false)
                ->get()),
            'campaign' => new CampaignSelectResource($this->campaign),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'date' => $this->date,
            'status' => $this->status,
            'user_name' => $this->user_name,
            'user_phone' => $this->user_phone,
            'user_email' => $this->user_email,
            'is_enable' => $this->is_enable,
            'is_deleted' => $this->is_deleted,
            'data_customer' => $cus,
            'fid' => $fid,
            'last_update_by' => UserRelationSelectResource::collection(DB::table('users')
                ->where('id','=',$this->last_update_by)
                ->get()),
            'created_at' => $this->updated_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
