<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;
class LaneResource extends JsonResource
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
            'order' => $this->order,
            'number' => $this->number,
            'photo' => PhotoResource::collection(DB::table('photo')
                ->join('lane_photos', 'photo.id', '=', 'lane_photos.photo_id')
                ->where('lane_photos.lane_id','=',$this->id)
                ->where('lane_photos.is_deleted','=',false)
                ->select('photo.*')
                ->get()),
            'weekday_start_time' => $this->weekday_start_time,
            'weekday_end_time' => $this->weekday_end_time,
            'holiday_start_time' => $this->holiday_start_time,
            'holiday_end_time' => $this->holiday_end_time,
            'visit_time' => $this->visit_time,
            'store_id' => $this->store_id,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
