<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Campaign;
use App\Models\CampaignStore;
use App\Models\Plan;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

/**
 * Class BookCalendarController
 *
 * @package App\Http\Controllers
 */
class BookCalendarController extends Controller
{
    protected $removeDateWhenNotLogin = [];
    const END_WORKING_HOUR = 17;

    public function getCalendar($campaign = null, $store = null)
    {
        if (!Campaign::ischeckExistCampaignWithStore($campaign, $store)) {
            return abort(404);
        }

        $result = $this->getInforCalendarData($campaign, $store);

        $dateTel = $result['dateTel'];
        $dataBook = $result['dataBook'];
        $calendar = $result['calendar'];
        $store_name = $result['store_name'];
        $store_code = $result['store_code'];
        $phone = $result['phone'];
        $stores = $result['stores'];
        $campaign_name = $result['campaign_name'];
        $calendarInfo = $result['calendarInfo'];
        $is_admin = $this->isAdmin();
        return view('frontend.calendar', compact('calendar', 'dataBook', 'phone', 'dateTel', 'stores',
            'store_name', 'store_code', 'campaign_name', 'calendarInfo', 'is_admin'));
    }

    public function searchCalendar($campaign = null, $store = null)
    {
        $result = $this->getInforCalendarData($campaign, $store);
        $calendarResult = json_decode($result['calendar'], true);
        foreach ($calendarResult as &$calendar) {
            $calendar['title'] = stripslashes($calendar['title']);
        }

        return response()->json(array('success' => true,
            [
                'phone' => $result['phone'],
                'dateTel' => $result['dateTel'],
                'store_name' => $result['store_name'],
                'store_code' => $result['store_code'],
                'calendar' => json_encode($calendarResult),
                'dataBook' => $result['dataBook']
            ]));
    }

    private function getInforCalendarData($campaign, $store)
    {
        $campaignData = Campaign::where('code', $campaign)->first();
        $storeData = Store::getStoreByStoreCode($store);
        $stores = $this->getStoreByCampaign($campaignData['id']);
        $campaignOption = Campaign::getCampaignOption($campaign);
        $arrayDateCampaign = $this->arrayDateCampaign($campaignOption, $storeData, $campaignData['is_display_calendar']);
        $dataBook = $this->getAllPlanOfCampaign($storeData, $campaignData, $arrayDateCampaign);
        $totalPlan = $this->totalPlan($dataBook);
        $usedPlan = $this->usedPlan($dataBook);
        $calendar = $this->getDataCalendar($arrayDateCampaign, $totalPlan, $usedPlan, $campaignData['is_display_calendar']);
        $calendarInfo = $this->getTelDayCalendar($calendar, $storeData, $campaignData['id'], $dataBook);

        $result = array(
            "dateTel" => json_encode($calendarInfo['telDay']),
            "dataBook" => json_encode($dataBook),
            "calendar" => json_encode($calendarInfo['buildCalendar']),
            "store_name" => $storeData['name'],
            "store_code" => $storeData['code'],
            "phone" => $storeData['store_phone'],
            "stores" => $stores,
            "campaign_name" => $campaignData['web_name'],
            "calendarInfo" => json_encode($calendarInfo['data'])
        );

        return $result;
    }

    protected function totalPlan($dataBook)
    {
        if (!$dataBook) {
            return array();
        }

        $totalPlan = array();

        foreach ($dataBook as $date => $data) {
            $totalPlan[$date] = $data['total'];
        }

        return $totalPlan;
    }

    protected function usedPlan($dataBook)
    {
        if (!$dataBook) {
            return array();
        }

        $usedPlan = array();

        foreach ($dataBook as $date => $data) {
            $usedPlan[$date] = $data['used'];
        }

        return $usedPlan;
    }

    protected function getTelDayCalendar($calendar, $store, $campaignId, &$dataBook)
    {
        $calendar['telDay'] = array();
        $today = Helper::getDateTimeWithCarbon()::today();

        $campaignRangerTime = Campaign::getCampaignOptionRanger($campaignId);
        if (empty($campaignRangerTime) || sizeof($calendar['buildCalendar']) == 0 || $this->isLogin()) {
            return $calendar;
        }

        $rangerStart = Helper::carbonParseTime($campaignRangerTime->start_time);
        $rangerEnd = Helper::carbonParseTime($campaignRangerTime->end_time);

        if (!$this->isAvaiableTime($store, $rangerStart, true) && ($today->lte($rangerStart))) {
            $rangerStart->addDays(1);
        }

        if (!$this->isAvaiableTime($store, $rangerEnd, false) && ($today->lte($rangerStart))) {
            $rangerStart->subDays(1);
        }

        $fixRangerStart = $rangerStart->hour(0)->minute(0)->second(0);
        $fixRangerEnd = $rangerEnd->hour(0)->minute(0)->second(0);
        $now = Helper::getDateTimeWithCarbon()::now();
        $numberDayAdd = $now->lt($today->hour(static::END_WORKING_HOUR)) ? 3 : 4;
        $isFirst = true;
        for ($day = $today->copy(); $day->lt($today->copy()->addDays($numberDayAdd)); $day->addDays(1)) {

            // check on range campaign
            if ($day->lt($fixRangerStart) || $day->gt($fixRangerEnd)) {
                continue;
            }

            $calendar['buildCalendar'] = $this->deleteOldDataCalendar($calendar['buildCalendar'], $day->toDateString(), $dataBook);

            if ($now->gt($today->hour(static::END_WORKING_HOUR)) && $isFirst){
                $isFirst = false;
                continue;
            }

            array_unshift($calendar['buildCalendar'], $this->renderTelDayOfCalendar($day->toDateString(), $store));
            array_unshift($calendar['telDay'], $day->toDateString());

            $isFirst = false;
        };

        return $calendar;
    }

    protected function deleteOldDataCalendar($buildCalendar, $day, &$dataBook)
    {
        for ($i = 0; $i < sizeof($buildCalendar); $i++) {
            if ($buildCalendar[$i]['start'] == $day) {
                unset($buildCalendar[$i]);
                break;
            }
        }
        $newBuildCalendar = array_values($buildCalendar);

        //delete databook when not login
        if (isset($dataBook[$day])){
            unset($dataBook[$day]);
        }

        return $newBuildCalendar;
    }

    protected function getAllPlanOfCampaign($store, $campaign, $dataCalendar)
    {
        $allData = array();

        foreach ($dataCalendar as $date => $data) {
            if (in_array($date, $this->removeDateWhenNotLogin)) {
                continue;
            }
            $campaignOptionId = $data['campaign_option_id'];
            $content = json_decode($data['content']);
            $benefits = $data['benefits'];
            $dateType = $data['date_type'];
            $price = $data['price'];
            $plans = Plan::getReservationPlan($store['id'], $campaign['photo_id'], $date);
            $listPlan = Plan::getDefaultPlanOneDay($date, $campaign, $store, $plans, $dateType);
            $allData[$date] = array(
                'date' => $date,
                'campaign' => array(
                    "code" => $campaign->code,
                    "name" => $campaign->name,
                    "web_name" => $campaign->web_name,
                ),
                'store' => array(
                    "code" => $store['code'],
                    "name" => $store['name'],
                ),
                'date_type' => $dateType,
                "option_id" => $campaignOptionId,
                "content" => $content,
                "benefits" => $benefits,
                'price' => $price,
                'plan_list' => $listPlan['schedule'],
                'total' => $listPlan['total'],
                'used' => $listPlan['used'],
                'lane_number' => $listPlan['lane_number'],
            );
        }

        return $allData;
    }

    protected function getDataCalendar($data, $totalPlan, $usedPlan, $display)
    {
        $buildCalendar = array();

        foreach ($data as $day => $price) {
            $status = $this->getStatusPlan($usedPlan[$day], $totalPlan[$day]);
            $calendar = $this->renderCampaignOfCalendar($day, $price['price'], $status, $display);
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
        }

        if ($percent >= 100) {
            return 2;
        }

        return 1;
    }

    protected function arrayDateCampaign($campaignOption, $store, $isDisplayCalendar)
    {
        $data = array();
        $removeKey = array();

        foreach ($campaignOption as $campaign) {
            //override data if it have
            $data = $this->getDataCalendarCampaign($store, $data, $campaign, $isDisplayCalendar);
        }

        foreach ($data as $day => $price) {
            if ($this->isDayNotWorking($store, $day)) {
                array_push($removeKey, $day);
            }
        }

        if (!empty($removeKey)) {
            foreach ($removeKey as $key) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    protected function getDataCalendarCampaign($store, $data, $campaignOption, $isDisplayCalendar)
    {
        $dateRange = $this->getDateRange($store, $campaignOption->start_time, $campaignOption->end_time);

        foreach ($dateRange as $date) {
            $price = $isDisplayCalendar ? $this->getPrice($campaignOption, $date) : 0;
            $price = $price != '' ? $price : 0;
            $data[$date]['price'] = $price;
            $data[$date]['campaign_option_id'] = $campaignOption->id;
            $data[$date]['content'] = $campaignOption->content;
            $data[$date]['date_type'] = Helper::checkHoliday($date);
            $data[$date]['benefits'] = $data[$date]['date_type'] == 1 ? $campaignOption->weekday_benefits : $campaignOption->holiday_benefits;
        }

        return $data;
    }

    protected function getPrice($campaign, $day)
    {
        return Helper::checkHoliday($day) == 2 ? $campaign->holiday_fee : $campaign->weekday_fee;
    }

    protected function isBetweenDay($day, $start, $end)
    {
        $start = Helper::carbonParseTime($start);
        $end = Helper::carbonParseTime($end);
        return Helper::carbonParseTime($day)->between($start, $end);
    }

    protected function renderCampaignOfCalendar($day, $price, $status = 1, $display)
    {
        $statusIcon = ['〇', '△', '×'];
        $color = $status == 0 ? '#D6F3D8' : ($status == 1 ? '#F6F7EA' : '#F8E5E8');
        $date = Helper::carbonParseTime($day)->format('d');

        if ($display == 0) {
            $calendar = array(
                'title' => '<span class=\"day-fullcalendar\">' . $date . '</span><span class=\"title-fullcalendar text-info\">' . $statusIcon[$status] . '</span><span class=\"title-fullcalendar\"></span>',
                'start' => $day,
                'rendering' => 'background',
                'color' => $color
            );
        } else {
            $calendar = array(
                'title' => '<span class=\"day-fullcalendar\">' . $date . '</span><span class=\"title-fullcalendar text-info\">' . $statusIcon[$status] . '</span><span class=\"title-fullcalendar\">' . $price . '円</span>',
                'start' => $day,
                'rendering' => 'background',
                'color' => $color
            );
        }


        return $calendar;
    }

    protected function renderTelDayOfCalendar($day, $store)
    {
        $phoneNumber = $store['store_phone'];
        $color = '#E8F8F8';
        $date = Helper::carbonParseTime($day)->format('d');
        $calendar = array(
            'title' => '<span class=\"day-fullcalendar\">' . $date . '</span><span class=\"title-fullcalendar\"><i class=\"fa fa-phone\"></i></span><span class=\"title-fullcalendar\"></span>',
            'start' => $day,
            'rendering' => 'background',
            'color' => $color
        );

        return $calendar;
    }

    protected function getDateRange($store, $startDate, $endDate, $dateTimeFormat = "Y-m-d", $timezone = 'Asia/Tokyo')
    {
        $range = [];
        $startDate = Helper::carbonParseTime($startDate);
        $endDate = Helper::carbonParseTime($endDate);
        $today = Helper::getDateTimeWithCarbon()::today();

        // compare date
        for ($date = $startDate->copy(); $date->lte($endDate->copy()); $date->addDay()) {
            if ($date->lessThan($today)) {
                continue;
            }
            $range[] = $date->format($dateTimeFormat);
        }

        // compare last day with hour
        $endDay = $endDate->format($dateTimeFormat);
        $defaultEndDay = Helper::getDateTimeWithCarbon()::createFromFormat(Carbon::DEFAULT_TO_STRING_FORMAT,
            $endDay . ' 00:00:00', $timezone);

        if ($defaultEndDay->lt($endDate) && !$defaultEndDay->eq($endDate) && ($today->eq($defaultEndDay))) {
            $range[] = $endDay;
        }

        if (!$this->isAvaiableTime($store, $startDate, true) && ($today->lte($startDate))) {
            array_shift($range);
        }

        if (!$this->isAvaiableTime($store, $endDate, false) && ($today->lte($startDate))) {
            array_pop($range);
        }

        return $range;
    }

    private function validateDate($date, $format = 'Y/m/d')
    {
        $d = Helper::carbonParseTime($date);
        return $d && $d->format($format) === $date;
    }

    private function validateSimple($date, $format = 'm/d')
    {
        $d = Helper::carbonParseTime($date);
        return $d && $d->format($format) === $date;
    }

    private function convertFullDate($arrayFixDay)
    {
        $this_year = date("Y");

        // check and convert date format with this year.
        $fullDateOff = array();

        foreach ($arrayFixDay as $date) {
            if (!Helper::validateDateTime($date, 'Y/m/d') && !Helper::validateDateTime($date, 'm/d'))
                continue;

            if ($this->validateSimple($date)) {
                $newFormat = $this_year . '/' . $date;
            } else {
                $newFormat = $date;
            }

            array_push($fullDateOff, $newFormat);
        }

        return $fullDateOff;
    }

    private function multiexplode($delimiters, $string)
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }

    protected function isDayNotWorking($store, $date)
    {
        $date = Helper::carbonParseTime($date);
        $dayOfWeek = $date->copy()->dayOfWeekIso;

        $dateString = $date->copy()->format('Y/m/d');
        switch ($dayOfWeek) {
            case 1:
                $isWork = $store['day_off_monday'];
                break;
            case 2:
                $isWork = $store['day_off_tuesday'];
                break;
            case 3:
                $isWork = $store['day_off_wednesday'];
                break;
            case 4:
                $isWork = $store['day_off_thursday'];
                break;
            case 5:
                $isWork = $store['day_off_friday'];
                break;
            case 6:
                $isWork = $store['day_off_saturday'];
                break;
            case 7:
                $isWork = $store['day_off_sunday'];
                break;
        }
        $stringFixDayOff = $store['fixed_days_off'];

        if ($stringFixDayOff != '') {
            $arrayFixDayOff = $this->multiexplode(array(",", "、", " "), $stringFixDayOff);

            if (in_array($dateString, $this->convertFullDate($arrayFixDayOff))) {
                $isWork = true;
            }
        }

        $stringFixDayOn = $store['fixed_days_on'];

        if ($stringFixDayOn != '') {

            $arrayFixDayOn = $this->multiexplode(array(",", "、"), $stringFixDayOn);
            if (in_array($dateString, $this->convertFullDate($arrayFixDayOn))) {
                $isWork = false;
            }
        }

        return $isWork;
    }

    /**
     * Check start campaign is avaiable when store open
     *
     * @param object $store
     * @param Carbon $timeCampaign
     * @param bool $isStartCampaign
     * @$isStartCampaign = true : start time campaign
     * @$isStartCampaign = false : end time campaign
     *
     * @return bool
     *
     * true if in the time open of store
     * false if not in the time open of store
     *
     */
    protected function isAvaiableTime($store, $timeCampaign, $isStartCampaign = true)
    {

        if ($timeCampaign->dayOfWeekIso == 6 || $timeCampaign->dayOfWeekIso == 7) {
            return $this->parseAvailableArguments($store, $timeCampaign, $isStartCampaign, 'holiday');
        }

        return $this->parseAvailableArguments($store, $timeCampaign, $isStartCampaign, 'weekday');
    }

    /**
     * @param        $store
     * @param        $timeCampaign
     * @param bool $isStartCampaign
     * @param string $nameFile
     *
     * @return bool
     */
    protected function parseAvailableArguments($store, $timeCampaign, $isStartCampaign = true, $nameFile = 'holiday')
    {
        if ($isStartCampaign) {
            $timeCompare = Helper::carbonParseTime($timeCampaign->format('Y-m-d') . ' ' . $store[$nameFile . '_end_time']);
            return $timeCompare->gt($timeCampaign);
        }

        $timeCompare = Helper::carbonParseTime($timeCampaign->format('Y-m-d') . ' ' . $store[$nameFile . '_start_time']);
        return $timeCompare->lt($timeCampaign);
    }

    protected function compareDatetimeWithDay($firstDate, $secondDate, $type = '>', $format = 'Y-m-d')
    {
        $date1 = Helper::carbonParseTime($firstDate);
        $date2 = Helper::carbonParseTime($secondDate);

        switch ($type) {
            case '>':
                return $date1->gt($date2);
            case '<':
                return $date1->lt($date2);
            case '=':
                return $date1->eq($date2);
            case '>=':
                return $date1->gte($date2);
            case '<=':
                return $date1->lte($date2);
            default:
                return null;
        }
    }

    public function getBooking(Request $request)
    {
        $listData = [];
        $listItem = [];
        $campaigns = Campaign::where('is_enable', true)->where('is_deleted', 0)->orderBy('updated_at', 'desc')->get();
        $stores = [];
        foreach ($campaigns as $index => $campaign) {
            $storeData = $this->getStoreByCampaign($campaign['id']);
            $data = [];
            if (empty($storeData)) {
                continue;
            }

            $data['new'] = false;
            $data['last_update'] = false;

            // new create check
            if ($campaign['created_at'] == $campaign['updated_at']) {
                $time = Helper::getDateTimeWithCarbon()::parse($campaign['created_at']);
                if ($time->diffInHours(Carbon::now()->setTimezone('Asia/Tokyo')) < 24) {
                    $listData[$index]['new'] = true;
                }
            } else {
                // update check
                $time = Helper::getDateTimeWithCarbon()::parse($campaign['updated_at']);
                if ($time->diffInHours(Carbon::now()->setTimezone('Asia/Tokyo')) < 24) {
                    $listData[$index]['last_update'] = true;
                }
            }

            $data['web_name'] = $campaign['web_name'];
            $data['name'] = $campaign['name'];
            $data['campaign_code'] = $campaign['code'];
            $data['id'] = $campaign['id'];

            foreach ($storeData as $key => $store) {
                if (!in_array($store, $stores)) $stores[] = $store;
                $data['store_code'] = $store['code'];
                $data['store_name'] = $store['name'];
                $data['store_id'] = $store['id'];
                array_push($listItem, $data);
            }
        }

        $currentPage = Paginator::resolveCurrentPage();
        $col = collect($listItem);
        $perPage = 20;
        $currentPageItems = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $items = new Paginator($currentPageItems, count($col), $perPage);
        $items->setPath($request->url());
        $items->appends($request->all());
        $listData = ($items->toArray())['data'];

        return view('frontend.campaign', compact('listData', 'campaigns', 'stores', 'items'));
    }

    public function getStoreByCampaign($camid)
    {
        $storeids = CampaignStore::select('store_id')->where('campaign_id', $camid)->where('is_deleted', 0)->get()->toArray();

        return Store::select(['id', 'code', 'name'])
            ->whereIn('id', $storeids)->where('is_enable', true)->where('is_deleted', 0)->get()->toArray();
    }

    function search(Request $request)
    {
        $query = $request->get('query');
        $data = (new CampaignStore())->searchCampaign($query);
        $output = '';
        foreach ($data as $item) {
            $output .= '<li class="col-md-3 col-sm-4 col-6 mb-3">
                <a href="calendar/' . $item['campaign_code'] . '/' . $item['store_code'] . '"><h5 class="title-campaign">'
                . $item['campaign_web_name'] . '</h5><img class="img-responsive" src="../images/default.jpg"/></a></li>';
        }
        echo $output;
    }

}
