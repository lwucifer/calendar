<?php

namespace App\Models;

use App\Helpers\Helper;
use Carbon\Carbon;
use DB;

class Plan extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    const STATUS_REQUEST = 0;
    const STATUS_NEW = 1;
    const STATUS_CONFIRM = 2;
    const STATUS_CANCEL = 3;
    const STATUS_DONE = 4;

    protected $table = 'plan';
    protected $fillable = ['id', 'name', 'store_id', 'campaign_id', 'date',
        'start_time', 'end_time', 'status', 'user_id', 'is_enable', 'comment', 'reminder_mail',
        'last_update_by', 'is_deleted', 'created_at', 'updated_at', 'data_customer'
        , 'user_name', 'user_email', 'user_phone','token'];
    protected $field_require = ['store_id', 'campaign_id', 'date',
        'start_time', 'end_time', 'status'];

    /**
     * One to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    /**
     * One to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * One to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign');
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

        $result['date'] = 'date_format:Y-m-d|required';
        $result['start_time'] = 'required';
        $result['end_time'] = 'after:start_time|required';
        $result['store_id'] = 'integer|required';
        $result['campaign_id'] = 'integer|required';
        $result['user_id'] = 'integer|required';
        $result['status'] = 'integer|required';

        return $result;
    }

    protected function getConditionManager($query, $current_id)
    {
        $tmp = User::where('id', '=', $current_id)->first();

        $query->where('store_id', $tmp->store_id);

        return $query;
    }

    public function lastUpdate()
    {
        return self::whereHas('user', function ($query) {
            $query->where('id', $this->fillable['last_update_by']);
        });
    }

    public function getReversionByDate($date, $store) {
        $query = self::where('date', '=', $date)->where('store_id', '=', $store)
            ->orderBy('start_time', 'asc');
        return $this->default_query_list($query);
    }

    public function search($input, $roleName, $current_id, $pagination)
    {
        $query = self::where(function ($query) use ($input) {
            foreach ($input as $key => $value) {
                if ($value == null) {
                    continue;
                }
                switch ($key) {
                    case 'store_id':
                    case 'campaign_id':
                    case 'status':
                        $query = $query->where($key, $value);
                        break;
                    case 'email':
                        $query = $query->where('user_email', 'LIKE', '%' . $value . '%');
                        break;
                    case 'phone':
                        $query = $query->where('user_phone', 'LIKE', '%' . $value . '%');
                        break;
                    case 'username':
                        $query = $query->where('user_name', 'LIKE', '%' . $value . '%');
                        break;
//				    case 'email':
//                    case 'phone':
//					    $list_id = DB::table('plan')
//						    ->join('users', 'plan.user_id', '=', 'users.id')
//						    ->where('users.' . $key, 'LIKE', '%' . $value . '%')
//						    ->select('plan.id')
//						    ->get()->toarray();
//
//					    if (!empty($list_id)) {
//						    $query = $query->where(function ($query) use ($list_id) {
//							    foreach ($list_id as $element) {
//								    $query = $query->orwhere('id', $element->id);
//							    }
//						    });
//					    } else {
//						    // empty search
//						    $query = $query->where('id', '-1');
//					    }
//					    break;
//				    case 'username':
//					    $list_id = DB::table('plan')
//						    ->join('users', 'plan.user_id', '=', 'users.id')
//						    ->where('users.first_name', 'LIKE', '%' . $value . '%')
//						    ->orWhere('users.last_name', 'LIKE', '%' . $value . '%')
//						    ->orWhere('users.kana_first_name', 'LIKE', '%' . $value . '%')
//						    ->orWhere('users.kana_last_name', 'LIKE', '%' . $value . '%')
//						    ->select('plan.id')
//						    ->get()->toarray();
//
//					    if (!empty($list_id)) {
//						    $query = $query->where(function ($query) use ($list_id) {
//							    foreach ($list_id as $element) {
//								    $query = $query->orwhere('id', $element->id);
//							    }
//						    });
//					    } else {
//						    // empty search
//						    $query = $query->where('id', '-1');
//					    }
//					    break;
                    case 'fid':
                        $customer_data = Plan::select()->where('data_customer', '<>', '{}')->where('data_customer', '<>', '')->get()->toArray();
                        $ids = [];
                        foreach ($customer_data as $item) {
                            if ($item['data_customer'] && $item['data_customer'] != '{}') {
                                if (stripos(json_decode($item['data_customer'], true)['fid'], $value) !== FALSE) {
                                    array_push($ids, $item['id']);
                                }
                            }
                        }
                        $query = $query->whereIn('id', $ids);
                        break;
                    case 'start_time':
                        $time = strtotime($value);
                        $list_data = DB::table('plan')->get()->toarray();

                        if (!empty($list_data)) {
                            $query = $query->where(function ($query) use ($list_data, $time) {
                                foreach ($list_data as $data) {
                                    if ($time > strtotime($data->date . ' ' . $data->end_time)) {
                                        $query = $query->where('id', '!=', $data->id);
                                    }
                                }
                            });
                        }
                        break;
                    case 'end_time':
                        $time = strtotime($value);
                        $list_data = DB::table('plan')->get()->toarray();

                        if (!empty($list_data)) {
                            $query = $query->where(function ($query) use ($list_data, $time) {
                                foreach ($list_data as $data) {
                                    if ($time < strtotime($data->date . ' ' . $data->start_time)) {
                                        $query = $query->where('id', '!=', $data->id);
                                    }
                                }
                            });
                        }

                        break;
                    default:
                        break;
                }
            }
        });

        if (Role::isManager($roleName) || Role::isUser($roleName)) {
            $query = $this->getConditionManager($query, $current_id);
        }

        return $this->default_query($query, $pagination);
    }

    public function CheckValidateOption($contents)
    {
        for ($i = 0; $i < sizeof($contents); $i++) {
            $selects = $contents[$i]['select'];
            for ($j = 0; $j < sizeof($selects); $j++) {
                if ($selects[$j]['status'] == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    public function checkValidateTime($date, $time)
    {
        $checkTime = (new Carbon())->setTimezone('Asia/Tokyo')::parse($date . ' ' . $time);
        $currentTime = Carbon::now();

        return $checkTime->gt($currentTime);
    }

    public static function buildReservationPlan($store_id, $photo_id , $date)
    {
        $curLane = LanePhoto::getLaneByStoreAndPhoto($store_id, $photo_id);
        $listPhoto = LanePhoto::getListPhotoIdByLaneId($curLane['id']);

        return DB::table('plan')
            ->select(['plan.id', 'plan.start_time', 'plan.end_time', 'plan.status'])
            ->join('campaigns', 'campaigns.id', '=', 'plan.campaign_id')
            ->join('lanes', 'lanes.store_id', '=', 'plan.store_id')
            ->where('lanes.id', $curLane['id'])
            ->where('plan.store_id', $store_id)
            ->where('plan.date', $date)
            ->where('plan.is_deleted', false)
            ->where('plan.status', Plan::STATUS_CONFIRM)
            ->whereIn('campaigns.photo_id', $listPhoto);
    }

    public static function getReservationPlan($store_id, $photo_id, $date)
    {
        $result = self::buildReservationPlan($store_id, $photo_id, $date)->get();

        if (empty($result)) {
            return [];
        }

        return $result->toArray();
    }

    public static function getReservationPlanEditMode($plan_id, $store_id, $photo_id, $date)
    {
        $result =self::buildReservationPlan($store_id, $photo_id, $date)
            ->where('plan.id', '!=', $plan_id)
            ->get();

        if (empty($result)) {
            return [];
        }

        return $result->toArray();
    }

    public static function getDefaultPlanOneDay($date, $campaign, $store, $plans, $dateType = 1)
    {
        $rangeTime = $campaign['time'];
        $formatTime = 'H:i:s';
        $listSchedule = array();

        $campaignRangerTime = Campaign::getCampaignOptionRanger($campaign['id']);
        $rangerStart = Helper::carbonParseTime($campaignRangerTime->start_time);
        $rangerEnd = Helper::carbonParseTime($campaignRangerTime->end_time);

        $curLane = LanePhoto::getLaneByStoreAndPhoto($store['id'], $campaign['photo_id']);
        $totalCount = 0;
        $totalUseCount = 0;

        $tmpStart = $dateType == 1 ? $curLane['weekday_start_time'] : $curLane['holiday_start_time'];
        $tmpEnd = $dateType == 1 ? $curLane['weekday_end_time'] : $curLane['holiday_end_time'];
        $laneStartTime = Helper::carbonParseTime($date . ' ' . $tmpStart);
        $laneEndTime = Helper::carbonParseTime($date . ' ' . $tmpEnd);
        $timeLaneStartWork = Helper::roundTimeWork($laneStartTime->second(0), true);
        $timeLaneEndWork = Helper::roundTimeWork($laneEndTime->second(0), false);

        // convert to compare date with campaign start day
        $compare_current_day = Helper::carbonParseTime($date . ' 00:00:00');
        $campaign_start_day = Helper::carbonParseTime($rangerStart);
        $campaign_start_day = $campaign_start_day->hour(0)->minute(0)->second(0);

        // case start time campaign day foreach with start hour of campaign
        if ($campaign_start_day == $compare_current_day) {
            $actual_start_time = Helper::roundTimeWork($rangerStart->second(0), true);
            $actual_end_time = Helper::roundTimeWork($laneEndTime->second(0), false);
        } // foreach with start hour of lane
        else {
            $actual_start_time = $timeLaneStartWork;
            $actual_end_time = $timeLaneEndWork;
        }

        for ($frame = $actual_start_time->copy(); $frame->lt($actual_end_time); $frame->addMinutes($rangeTime)) {

            $beginTime = $actual_start_time->copy()->format($formatTime);
            $curActualTime = $frame->copy()->format($formatTime);

            if ($beginTime != $curActualTime) {
                $frame->addMinutes($curLane['visit_time']);
            }

            $endFrame = $frame->copy()->addMinutes($rangeTime);
            $startTime = $frame->format($formatTime);
            $endTime = $endFrame->format($formatTime);

            $curStartTime = Helper::carbonParseTime($date . ' ' . $startTime);
            $curEndTime = Helper::carbonParseTime($date . ' ' . $endTime);

            // time over 24h
            if ($curEndTime < $curStartTime)
                continue;

            // Check select time as ranger of current lane time
            if ($curStartTime->lt($laneStartTime) || $curEndTime->gt($laneEndTime))
                continue;

            // Check select time as ranger of current campaign time
            if ($curStartTime->lt($rangerStart) || $curEndTime->gt($rangerEnd))
                continue;

            $tmpCount = $curLane['number'];

            $tmpUsedCount = 0;

            if(!empty($plans)) {

                foreach ($plans as $plan) {
                    $tempStartTime = Helper::carbonParseTime($date . ' ' . $plan->start_time);
                    $tempEndTime = Helper::carbonParseTime($date . ' ' . $plan->end_time);

                    // check if has plan booked
                    if ($curEndTime->lte($tempStartTime) || $curStartTime->gte($tempEndTime)) {
                        continue;
                    }

                    $tmpUsedCount++;
                }
            }

            $arrayDataPlan = array(
                "time_start" => $startTime,
                "time_end" => $endTime,
                "status" => $tmpCount - $tmpUsedCount,
            );
            array_push($listSchedule, $arrayDataPlan);

            $totalCount++;
            if ($tmpCount - $tmpUsedCount <= 0) {
                $totalUseCount++;
            }
        }

        $result = array(
            "total" => $totalCount,
            "used" => $totalUseCount,
            "schedule" => $listSchedule,
            "lane_number" => $curLane['number'],
        );

        return $result;
    }

    public static function getListData($plan_id)
    {
        $plan = self::where('id', $plan_id)->first();
        if (empty($plan))
            return null;

        $campaign = Campaign::where('id', $plan['campaign_id'])->first();
        if (empty($campaign))
            return null;

        $store = Store::where('id', $plan['store_id'])->first();
        if (empty($store))
            return null;

        $date_type = Helper::checkHoliday($plan['date']);
        $plans = self::getReservationPlanEditMode($plan_id, $plan['store_id'], $campaign['photo_id'], $plan['date']);
        $listData = self::getDefaultPlanOneDay($plan['date'], $campaign, $store, $plans, $date_type);

        if (empty($listData))
            return null;

        // compare time with today and disable
        for ($i = 0; $i < count($listData['schedule']); $i++) {

            if ($plan['start_time'] == $listData['schedule'][$i]['time_start']
                && $plan['end_time'] == $listData['schedule'][$i]['time_end']) {
                continue;
            }

            if (Helper::carbonParseTime($plan['date'] . ' ' . $listData['schedule'][$i]['time_start'])
                ->lt(Carbon::now()->setTimezone('Asia/Tokyo'))) {
                $listData['schedule'][$i]['status'] = 0;
            }
        }

        $ret = array();
        $counter = 0;
        for ($i = 0; $i < count($listData['schedule']); $i++) {
            if ($listData['schedule'][$i]['status'] != 0) {
                $listData['schedule'][$i]['id'] = $counter;
                $counter++;
                array_push($ret, $listData['schedule'][$i]);
            }
        }

        return $ret;
    }
}
