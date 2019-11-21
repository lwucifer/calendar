<?php

namespace App\Http\Resources;

use App\Models\Lane;
use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class StoreResource extends JsonResource
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
            'manager' => UserRelationResource::collection(DB::table('users')
                ->where('id','=',$this->manager_id)
                ->get()),
            'manager_id' => $this->manager_id,
            'lane' => LaneResource::collection(Lane::where('store_id', $this->id)->where('is_deleted', false)
                ->orderBy('order', 'asc')->get()),
            'is_enable' => $this->is_enable,
            'phone' => $this->phone,
            'weekday_start_time' => $this->weekday_start_time,
            'weekday_end_time' => $this->weekday_end_time,
            'holiday_start_time' => $this->holiday_start_time,
            'holiday_end_time' => $this->holiday_end_time,
            'day_off_monday' => $this->day_off_monday,
            'day_off_tuesday' => $this->day_off_tuesday,
            'day_off_wednesday' => $this->day_off_wednesday,
            'day_off_thursday' => $this->day_off_thursday,
            'day_off_friday' => $this->day_off_friday,
            'day_off_saturday' => $this->day_off_saturday,
            'day_off_sunday' => $this->day_off_sunday,
            'fixed_days_off' => $this->fixed_days_off,
            'fixed_days_on' => $this->fixed_days_on,
            'comment' => $this->comment,
            'sign_email' => $this->sign_email,
            'last_update_by' => UserRelationResource::collection(DB::table('users')
                ->where('id','=',$this->last_update_by)
                ->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
