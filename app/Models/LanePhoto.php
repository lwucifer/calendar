<?php

namespace App\Models;

class LanePhoto extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lane_photos';

    protected $fillable = ['id','lane_id', 'photo_id','is_deleted','created_at','updated_at'];
    protected $field_require = ['lane_id', 'photo_id' ];

    public static  function  getLaneByStoreAndPhoto($store_id, $photo_id){
        $list_lane = self::where('photo_id', '=' , $photo_id)->select('lane_id')->distinct('photo_id')
            ->where('is_deleted', '=', false)->get()->toarray();

        $array_lane = self::convertArrayByKey($list_lane, 'lane_id');

        return Lane::whereIn('id', $array_lane)->where('store_id', '=', $store_id)
            ->where('is_deleted', '=', false)->first();
    }

    public static function getListPhotoIdByLaneId($lane_id) {
        return self::where('lane_id', $lane_id)->where('is_deleted', false)->select('photo_id')->distinct()->get()->toarray();
    }

}
