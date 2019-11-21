<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\API\BaseController;
use App\Models\Campaign;
use App\Models\Plan;
use App\Models\LanePhoto;
use App\Models\PlanCampaignOption;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/**
 * Class StoreCalendarController
 *
 * @package App\Http\Controllers
 */
class StoreCalendarController extends BaseController
{

    /** Overwrite this resource
     *
     * @var string
     */
    protected $resource = 'App\Http\Resources\PlanResource';

    public function getCalendar($id = null)
    {
        $storeData = $this->getStore($id);

        if (empty($storeData)) {
            return abort(404);
        }

        $storeData = $this->getStore($id);
        $campaignOption = $this->getOption($id);
        $arrayDateCampaign = $this->arrayDateCampaign($campaignOption);
        $dataBook = $this->getAllPlanOfCampaign($storeData, $arrayDateCampaign);
        $totalPlan = $this->totalPlan($dataBook);
        $calendar = $this->getDataCalendar($arrayDateCampaign, $id, $totalPlan);

        $data['data_book'] = $dataBook;
        $data['calendar'] = $calendar['buildCalendar'];
        $data['store_name'] = $storeData['name'];

        return $this->sendResponse($data,200);
    }

    protected function isCheckExistStore($storeId)
    {
        $store = Store::select('id')
            ->where('id', $storeId)
            ->where('is_enable', true)
            ->where('is_deleted', false);

        return empty($store) ? true : false;
    }

    protected function totalPlan($dataBook)
    {
        $totalPlan = [];

        if (!$dataBook) {
            return $totalPlan;
        }

        foreach ($dataBook as $date => $data) {
            $totalPlan[$date] = sizeof($data['plan_list']);
        }

        return $totalPlan;
    }

    protected function getStore($storeId)
    {
        return Store::select(['id', 'store.name as name', 'store.code as code', 'store.comment as comment', 'store.phone as store_phone',
            'weekday_start_time', 'weekday_end_time', 'holiday_start_time', 'holiday_end_time', 'day_off_monday',
            'day_off_monday', 'day_off_tuesday', 'day_off_wednesday', 'day_off_thursday', 'day_off_friday',
            'day_off_saturday', 'day_off_sunday', 'fixed_days_off', 'fixed_days_on'])
            ->where('id', $storeId)
            ->first()
            ->toArray();
    }

    protected function getPlans($store_id)
    {
        return Plan::where('store_id', $store_id)->where('is_deleted', 0)->get()->toArray();
    }

    protected function getOption($store_id)
    {
        $plans = Plan::select('id')->where('store_id', $store_id)->where('is_deleted', 0)
            ->where('status','<>', Plan::STATUS_CANCEL)
            ->orderBy('status', 'asc')->get()->toArray();
        return DB::table('plan_campaign_option')
            ->whereIn('plan_id', $plans)
            ->where('is_deleted',false)
            ->get()->toArray();
    }

    protected function groupData($array, $keyGroup)
    {
        $data = array();
        foreach ($array as $key => $value) {
            $data[$value[$keyGroup]][] = $value;
        }

        ksort($data, SORT_NUMERIC);
        return $data;
    }

    protected function formatTime($time, $formatTime = 'H:i')
    {
        return $this->parseCarbon($time)->format($formatTime);
    }

    protected function getAllPlanOfCampaign($storeData, $dataCalendar)
    {
        $allData = array();
        foreach ($dataCalendar as $date => $data) {
            $plans = [];
            $dateType = $data[0]['date_type'];
            $range_time = $this->getStoreTimeRange($storeData, $dateType, $date);
            $start_store = $this->parseCarbon($range_time[0]);
            foreach ($data as $key => $item) {
                $lane = $this->getLane($item['plan']['store_id'], $item['plan']['campaign_id']);
                $end = $this->parseCarbon($item['plan']['start_time']);
                $start = $this->parseCarbon($item['plan']['end_time']);
                $height = $end->diffInMinutes($start) / 30;
                $start_time = $this->parseCarbon($item['plan']['start_time']);
                $top = $start_store->diffInMinutes($start_time) / 30;
                $plan_new['lane_id'] = $lane['id'];
                $plan_new['lane_name'] = $lane['name'];
                $plan_new['campaign_id'] = $item['plan']['campaign']['id'];
                $plan_new['campaign_name'] = $item['plan']['campaign']['name'];
                $plan_new['user_name'] = $item['plan']['user_name'];
                $plan_new['user_phone'] = $item['plan']['user_phone'];
                $plan_new['user_email'] = $item['plan']['user_email'];
                $plan_new['start_time'] = $this->formatTime($item['plan']['start_time']);
                $plan_new['end_time'] = $this->formatTime($item['plan']['end_time']);
                $plan_new['content'] = $this->reBuildOption($item['content']);
                $plan_new['status'] = $item['plan']['status'];
                $plan_new['height'] = $height;
                $plan_new['top'] = $top;
                array_push($plans, $plan_new);
            }
            $plans = $this->groupData($plans, 'lane_id') ;
            $allData[$date] = array(
                'date' => $date,
                'date_type' => $dateType,
                'plan_list' => $plans,
                'range_time' => $range_time,
            );
        }

        return $allData;
    }

    protected function reBuildOption($content)
    {
        $data = [];
        foreach ($content as $option) {
            $status = 0;
            foreach ($option->select as $item) {
                if($item->status == 1) {
                    $status = 1;
                    break;
                }
            }
            if ($status == 1) {
                $data[] = $option;
            }
        }
        return $data;
    }

    protected function getPlan($storeId, $date)
    {
        return Plan::select(['start_time', 'end_time', 'status'])
            ->where('store_id', $storeId)
            ->where('date', $date)
            ->get()
            ->toArray();
    }

    protected function getDataCalendar($data, $storeId, $totalPlan)
    {
        $buildCalendar = array();

        foreach ($data as $day => $price) {
            $listPlan = $this->getPlan($storeId, $day);
            $countOrdered = sizeof($listPlan);
            $status = $this->getStatusPlan($countOrdered, $totalPlan[$day]);
            $calendar = $this->renderCampaignOfCalendar($day, 0, $status);
            array_push($buildCalendar, $calendar);
        }

        return array(
            'data' => $data,
            'buildCalendar' => $buildCalendar
        );
    }

    protected function getStatusPlan($countOrdered, $total)
    {
        if ($total == 0) {
            return 2;
        }

        $percent = $countOrdered / $total * 100;

        if ($percent <= 50) {
            return 0;
        } else if ($percent >= 100) {
            return 2;
        } else {
            return 1;
        }
    }

    protected function arrayDateCampaign($campaignOption)
    {
        $data = array();
        $date_array = [];
        foreach ($campaignOption as $key => $campaign) {
            $plan = new $this->resource(Plan::find($campaign->plan_id));
            $date = $plan['date'];
                $newData = [];
                $newData['option_id'] = $campaign->id;
                $newData['plan'] = $plan;
                $newData['content'] = json_decode($campaign->content);
                $newData['date_type'] = Helper::checkHoliday($date);
                $data[$date][] = $newData;
            array_push($date_array, $date);
        }

        return $data;
    }

    protected function renderCampaignOfCalendar($day, $price, $status = 1)
    {
        $statusIcon = ['〇', '△', '×'];
        $color = $status == 0 ? '#D6F3D8' : ($status == 1 ? '#F6F7EA' : '#F8E5E8');

        $calendar = array(
            'title' => '<br><span class=\"title-fullcalendar text-info\">' . $statusIcon[$status] . '</span><span class=\"title-fullcalendar\">' . $price . '円</span>',
            'start' => $day,
            'rendering' => 'background',
            'color' => $color
        );

        return $calendar;
    }

    protected function getDefaultPlanOneDay($date, $plan, $store, $dateType = 1)
    {
        $rangeTime = $plan['plan']['campaign']['time'];
        $timeOpenStore = $dateType == 1 ? $store['weekday_start_time'] : $store['holiday_start_time'];
        $timeCloseStore = $dateType == 1 ? $store['weekday_end_time'] : $store['holiday_end_time'];
        $formatTime = 'H:i';
        $listSchedule = array();
        $openStore = Carbon::parse($date . ' ' . $timeOpenStore);
        $closeStore = Carbon::parse($date . ' ' . $timeCloseStore);
        $timeActualStartWork = $this->roundTimeWork($openStore->second(0), true);
        $timeActualEndWork = $this->roundTimeWork($closeStore->second(0), false);

        for ($frame = $timeActualStartWork->copy(); $frame->lt($timeActualEndWork); $frame->addMinutes($rangeTime)) {
            $endFrame = $frame->copy()->addMinutes($rangeTime);
            $startTime = $frame->format($formatTime);
            $endTime = $endFrame->format($formatTime);
            $plan_data = $plan['plan'];
            if ($plan_data['start_time'] == $startTime) {
                $arrayDataPlan = array(
                    "time_start" => $plan_data['start_time'],
                    "time_end" => $plan_data['end_time'],
                    "status" => $plan_data['status'],
                    "content" => $plan['content'],
                );
                array_push($listSchedule, $arrayDataPlan);
            } else {
                $arrayDataPlan = array(
                    "time_start" => $startTime,
                    "time_end" => $endTime,
                    "status" => 0,
                    "content" => null,
                );
                array_push($listSchedule, $arrayDataPlan);
            }
        }
        return $listSchedule;
    }

    protected  function parseCarbon($time) {
        return Carbon::parse('2019-06-05' . ' ' . $time);
    }

    protected function getStoreTimeRange($store, $dateType = 1, $date = null)
    {
        $timeOpenStore = $dateType == 1 ? $store['weekday_start_time'] : $store['holiday_start_time'];
        $timeCloseStore = $dateType == 1 ? $store['weekday_end_time'] : $store['holiday_end_time'];
        $openStore = $this->parseCarbon($timeOpenStore);
        $closeStore = $this->parseCarbon($timeCloseStore);
        if ($date) {
            $plans = Plan::where('store_id', $store['id'])->where('date', $date);
            $plans_start = $this->parseCarbon($plans->min('start_time'));
            $plans_end = $this->parseCarbon($plans->max('end_time'));
            $openStore = $plans_start->lt($openStore) ? $plans_start : $openStore;
            $closeStore = $plans_end->lt($closeStore) ? $closeStore : $plans_end;
        }
        $formatTime = 'H:i';
        $listSchedule = array();
        $timeActualStartWork = Helper::roundTimeWork($openStore->second(0), true);
        $timeActualEndWork = Helper::roundTimeWork($closeStore->second(0), false);
        array_push($listSchedule, $timeActualStartWork->copy()->format($formatTime));
        for ($frame = $timeActualStartWork->copy(); $frame->lt($timeActualEndWork); $frame->addMinutes(30)) {
            $endFrame = $frame->copy()->addMinutes(30)->format($formatTime);
            array_push($listSchedule, $endFrame);
        }

        return $listSchedule;
    }

    protected function getLane($store, $campaign)
    {
        $photo = Campaign::where('id', $campaign)->first()->photo_id;
        return LanePhoto::getLaneByStoreAndPhoto($store, $photo);
    }

    public function dataStore(Request $request, $id = null)
    {
        if ($this->isCheckExistStore($id)) {
            return abort(404);
        }

        $startDate = $request->start_date ? $request->start_date : false;
        $endDate = $request->end_date ? $request->end_date : false;
        $campaignOption = $this->getOptionByDate($id, $startDate, $endDate);
        $arrayDateCampaign = $this->arrayDateCampaign($campaignOption);
        $dataBook = $this->getAllPlanOfCampaignNoRange($arrayDateCampaign);
        $data['data_book'] = $dataBook;

        return $dataBook;
    }

    protected function getOptionByDate($store_id, $startDate, $endDate)
    {
        $plans = Plan::select('id')->where('store_id', $store_id)->where('is_deleted', 0)->where('status','<>', Plan::STATUS_CANCEL);
        if ($startDate)
            $plans = $plans->whereDate('date', '>=', $startDate);
        if ($endDate)
            $plans = $plans->whereDate('date', '<=', $endDate);
        $plans = $plans->orderBy('status', 'asc')->get()->toArray();

        return DB::table('plan_campaign_option')
            ->whereIn('plan_id', $plans)
            ->where('is_deleted',false)
            ->get()->toArray();
    }

    protected function getAllPlanOfCampaignNoRange($dataCalendar)
    {
        $allData = array();

        foreach ($dataCalendar as $date => $data) {
            $plans = [];
            $dateType = $data[0]['date_type'];
            foreach ($data as $key => $item) {
                $lane = $this->getLane($item['plan']['store_id'], $item['plan']['campaign_id']);
                $plan_new['lane_id'] = $lane['id'];
                $plan_new['lane_name'] = $lane['name'];
                $plan_new['campaign_id'] = $item['plan']['campaign']['id'];
                $plan_new['campaign_name'] = $item['plan']['campaign']['name'];
                $plan_new['user_name'] = $item['plan']['user_name'];
                $plan_new['user_phone'] = $item['plan']['user_phone'];
                $plan_new['user_email'] = $item['plan']['user_email'];
                $plan_new['start_time'] = $this->formatTime($item['plan']['start_time']);
                $plan_new['end_time'] = $this->formatTime($item['plan']['end_time']);
                $plan_new['content'] = $this->reBuildOption($item['content']);
                $plan_new['status'] = $item['plan']['status'];
                array_push($plans, $plan_new);
            }
            $plans = $this->groupData($plans, 'lane_id') ;
            $allData[$date] = array(
                'date' => $date,
                'date_type' => $dateType,
                'plan_list' => $plans,
            );
        }

        return $allData;
    }
}
