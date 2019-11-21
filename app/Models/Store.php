<?php namespace App\Models;
use Carbon\Carbon;

class Store extends BaseModel  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store';

    protected $fillable = ['name' ,'code' , 'manager_id','is_enable','phone','weekday_start_time',
        'weekday_end_time','holiday_start_time','holiday_end_time','day_off_monday',
        'day_off_tuesday','day_off_wednesday','day_off_thursday','day_off_friday','day_off_saturday',
        'day_off_sunday','fixed_days_off','fixed_days_on',
        'comment','sign_email','last_update_by','created_at','updated_at'];

    protected $field_require = ['name'];

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function reservation()
    {
        return $this->hasMany('App\Models\Plan');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function lane()
    {
        return $this->hasMany('App\Models\Lane');
    }

    /**
     * Get ccndition of manager
     * @return array
     */
    protected function getConditionManager($query, $current_id)
    {
        $tmp = User::where('id', '=', $current_id)->first();
        $query->where('id', $tmp->store_id);
        return $query;
    }

    /** Validation Data
     * @return array
     */
    public function fieldSetValidate($id = null)
    {
        $key = 'required';
        $result = [];

        foreach ($this->field_require as $item) {
            $result[$item] = $key;
        }

        $result['name'] = 'string|required';
//        $result['manager_id'] = 'integer|required';
        $result['weekday_start_time'] = 'after:initial_time|required';
        $result['weekday_end_time'] = 'after:initial_time|required';
        $result['holiday_start_time'] = 'after:initial_time|required';
        $result['holiday_end_time'] = 'after:initial_time|required';
        $result['phone'] = 'digits:10|nullable';

        return $result;
    }

    public function search($input, $pagination) {
        $query = self::where(function ($query) use ($input) {
            foreach ($input as $key => $value) {
                if ($value == null) {
                    continue;
                }

                switch ($key) {
                    case 'name':
                        $query->where('name', 'LIKE', '%' . $value . '%');
                        break;
                    case 'code':
                        $query = $query->where('code', $value);
                        break;
                    default:
                        break;
                }
            }
        });

        return $this->default_query($query, $pagination);
    }

    private function convertTime($date, $time)
    {
        return (new Carbon())->setTimezone('Asia/Tokyo')::parse($date . ' ' . $time);
    }

    public function CheckValidationPhotoLane($lane_data)
    {
        $list_photo = array();

        foreach ($lane_data as $lane)
        {
            $list_select_photo = $lane['selected_photo'];
            foreach ($list_select_photo as $photo)
            {
                if (!in_array($photo['id'], $list_photo)) {
                    array_push($list_photo, $photo['id']);
                }
                else {
                    return false;
                }
            }
        }

        return true;
    }

    public function checkValidationTimeLane($input)
    {
        $date =  '1/1/2019';

        if(!isset($input['lanes']))
            return true;

        $w_start = self::convertTime($date, $input['weekday_start_time']);
        $w_end   = self::convertTime($date, $input['weekday_end_time']);
        $h_start = self::convertTime($date, $input['holiday_start_time']);
        $h_end   = self::convertTime($date, $input['holiday_end_time']);

        foreach ($input['lanes'] as $lane)
        {
            $w_start_lane = self::convertTime($date, $lane['weekday_start_time']);
            $w_end_lane   = self::convertTime($date, $lane['weekday_end_time']);
            $h_start_lane = self::convertTime($date, $lane['holiday_start_time']);
            $h_end_lane   = self::convertTime($date, $lane['holiday_end_time']);
            if( $w_start_lane->lt($w_start) || $w_end_lane->gt($w_end)
                || $h_start_lane->lt($h_start) || $h_end_lane->gt($h_end)){
                return false;
            }
        }

        return true;
    }

    public static function getStoreByStoreCode($storeCode) {
        return Store::select(['id', 'store.name as name', 'store.code as code', 'store.comment as comment', 'store.phone as store_phone',
            'weekday_start_time', 'weekday_end_time', 'holiday_start_time', 'holiday_end_time', 'day_off_monday',
            'day_off_monday', 'day_off_tuesday', 'day_off_wednesday', 'day_off_thursday', 'day_off_friday',
            'day_off_saturday', 'day_off_sunday', 'fixed_days_off', 'fixed_days_on'])
            ->where('code', $storeCode)->first()->toArray();
    }

    public static function getStoreByCampaignCode($campaignCode){
        return Store::join('campaign_stores', 'campaign_stores.store_id', '=', 'store.id')
            ->select(['store.id as id', 'store.name as name', 'store.code as code', 'store.comment as comment', 'store.phone as store_phone',
                'weekday_start_time', 'weekday_end_time', 'holiday_start_time', 'holiday_end_time', 'day_off_monday',
                'day_off_monday', 'day_off_tuesday', 'day_off_wednesday', 'day_off_thursday', 'day_off_friday',
                'day_off_saturday', 'day_off_sunday', 'fixed_days_off', 'fixed_days_on'])
            ->join('campaigns', 'campaigns.id', '=', 'campaign_stores.campaign_id')
            ->where('campaigns.code', $campaignCode)
            ->where('campaign_stores.is_deleted',false)
            ->get()->toArray();
    }
}
