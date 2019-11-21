<?php

namespace App\Http\Resources;

use App\Helpers\Helper;
use App\Models\Plan;
use Illuminate\Http\Resources\Json\JsonResource;
use DB;
class PlanResource extends JsonResource
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
            'campaign' => new CampaignRelationResource($this->campaign),
            'list_data' => Plan::getListData($this->id),
            'date' => $this->date,
            'start_time' => $this->start_time,
            'date_type' => Helper::checkHoliday($this->date),
            'end_time' => $this->end_time,
            'status' => $this->status,
            'user_name' => $this->user_name,
            'user_phone' => $this->user_phone,
            'user_email' => $this->user_email,
            'comment' => $this->comment,
            'is_enable' => $this->is_enable,
            'is_deleted' => $this->is_deleted,
            'data_customer' => $cus,
            'fid' => $fid,
            'last_update_by' => UserRelationResource::collection(DB::table('users')
                ->where('id','=',$this->last_update_by)
                ->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
