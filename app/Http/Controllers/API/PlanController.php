<?php

namespace App\Http\Controllers\API;

use App\Exports\PlansExport;
use App\Helpers\Helper;
use App\Http\Controllers\BaseRequestController;
use App\Http\Controllers\BookCalendarController;
use App\Models\Campaign;
use App\Models\FNumber;
use App\Models\LanePhoto;
use App\Models\Plan;
use App\Models\PlanCampaignOption;
use App\Models\Store;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Validator;
use Chumper\Zipper\Facades\Zipper as Zipper;

class PlanController extends BaseController
{
    protected $modal = 'App\Models\Plan';
    protected $resource = 'App\Http\Resources\PlanResource';
    private $collection = 'App\Http\Resources\PlanCollection';
    private $collectionPhoto = 'App\Http\Resources\PlanCollectionPhoto';
    private $status = false;

    /**
     * PlanController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function index()
    {
        $query = $this->modal::select();

        if ($this->isManger() || $this->isUser()) {
            $query = $this->modal::getConditionManager($query, $this->getUserLogin('id'));
        }

        return $this->sendResponse(
            new $this->collection(
                (new $this->modal)->default_query($query, $this->pagination)
            ),
            200);
    }

    /**
     * Overwrite Update the specified resource in storage.
     *
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function update($id, Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, (new $this->modal)->fieldSetValidate());

        if ($validator->fails()) {
            return $this->sendError(404, $validator->errors());
        }

        if (isset($input['content']) && ($input['content'] != null)) {
            $contents = json_decode($input['content'], true);
            $validate = array();
            $validate['option'] = trans('validation.attributes.select_option');
            if (!(new $this->modal)->CheckValidateOption($contents)) {
                return $this->sendError(404, $validate);
            }
        }

        $object = $this->checkIdRequest($id);

        if ($object === false) {
            return $this->sendError(404, $this->error_msg_no_object);
        }

        //send mail if change time
        if($object['start_time'] != $input['start_time']){
//            dd($object);
            $this->changeTimeReservation($object);
        }

        if ($object['status'] != $input['status']){
            $this->sendMailWithStatus($input['status'], $object);
        }

        foreach ((new $this->modal)->get_field_table() as $field) {
            if (isset($input[$field])) {
                $object->$field = $input[$field];
            }
        }

        $object->save();

        //save plan option
        if (isset($input['content']) && ($input['content'] != null)) {
            $planOption = PlanCampaignOption::where('plan_id', $object['id'])->first();
            $planOption['content'] = $input['content'];
            $planOption->save();
        }

        return $this->sendResponse(new $this->resource($object), 201);
    }


    protected function changeTimeReservation($object){
//        dd($object);
        $time = $object['start_time'] . ' - ' . $object['end_time'];

        $this->sendMailCancelBooking($object, $time);
        $this->sendMailRegisterToCustomer($object);
    }

    /** Search the specified resource in storage by key.
     *
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request)
    {
        return $this->sendResponse(new $this->collection(
            (new $this->modal)->search($request->all(), $this->userInfo['role']['name'],
                $this->getUserLogin('id'), $this->pagination)
        ), 200);
    }

    /** Search the specified resource in storage by key.
     *
     * @param Request $request
     *
     * @return array
     */
    public function getPlanInfo(Request $request)
    {
        $input = $request->all();
        $query = null;
        if(isset($input['date']) && isset($input['store']) && isset($input['token'])
            && $input['token'] == '1488fa37466fa2c95c33e6d086882ba0')
            return $this->sendResponse($this->resource::collection((new $this->modal)
                ->getReversionByDate($input['date'], $input['store'])), 200);

        return $this->sendError(404, $this->error_msg_no_object);
    }

    /**
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exports()
    {
        return (new PlansExport)->download($this->getExportFileName('plan'));
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function import(Request $request)
    {
        try {
            $data = \GuzzleHttp\json_decode($request->csv);
            if ($request->count < 10) {
                return $this->sendError(404, trans('validation.custom.import.no_data'));
            }
            $csv_data = array_slice($data, 1);
            $rowSuccess = collect();
            $rowErrors = collect();

            foreach ($csv_data as $index => $row) {
                $planRow = $this->convertRowToPlan($row);
                $checked = $this->isUpdateOrCreate($row);

                $rules = (new $this->modal)->fieldSetValidate();
                $validatorUser = Validator::make($planRow, $rules);

                if ($validatorUser->fails()) {
                    $rowErrors->push(['index' => $index, 'message' => $validatorUser->messages()]);
                    continue;
                }

                DB::beginTransaction();
                $plan = $checked === 0 ? Plan::find($row[0]) : new Plan();

                try {
                    $plan->store_id = $planRow['store_id'];
                    $plan->campaign_id = $planRow['campaign_id'];
                    $plan->user_id = $planRow['user_id'];
                    $plan->date = $planRow['date'];
                    $plan->start_time = $planRow['start_time'];
                    $plan->end_time = $planRow['end_time'];
                    $plan->comment = $planRow['comment'];
                    $plan->status = $planRow['status'];
                    $plan->is_enable = $planRow['is_enable'] ? $planRow['is_enable'] : 1;
                    $plan->save();

                    DB::commit();
                    $rowSuccess->push(['index' => $index]);
                } catch (\Exception $e) {
                    DB::rollback();
                    $rowErrors->push(['index' => $index, 'message' => $e->getMessage()]);
                }
            }

            return $this->sendResponse([
                'row' => count($csv_data),
                'rowSuccess' => $rowSuccess,
                'rowError' => $rowErrors
            ], 200);
        } catch (Exception $e) {
            return $this->sendError(500, $e->getMessage());
        }
    }

    /**
     * @param $row
     *
     * @return array
     */
    protected function convertRowToPlan($row)
    {
        return $this->convertRowToModal([
            'id',
            'store_id',
            'campaign_id',
            'user_id',
            'date',
            'start_time',
            'end_time',
            'comment',
            'status',
            'is_enable'
        ], $row);
    }

    public function create(Request $request)
    {
        $input = $request->all();

        $store = Store::where('code', $input['store_code'])->where('is_deleted', false)->first();
        $campaign = Campaign::where('code', $input['campaign_code'])->where('is_deleted', false)->first();

        if (empty($store) || empty($campaign))
            return Redirect::back()->withErrors(['exist' => trans('validation.attributes.not_exits')]);

        $input['campaign_id'] = $campaign['id'];
        $input['campaign_name'] = $campaign['name'];
        $input['store_id'] = $store['id'];
        $input['store_name'] = $store['name'];

        //validate for check time over current time
        if (!(new $this->modal)->checkValidateTime($input['date'], $input['start_time']) || $this->checkTime($input)) {
            return Redirect::back()->withErrors(['exist' => trans('validation.attributes.time_over')]);
        }

        $rowErrors = collect();

        if ($this->checkPlan($input)) {
            return Redirect::back()->withErrors(['exist' => trans('validation.attributes.full')]);
        }

        $input['user_id'] = 1;
        $input['token'] = Str::random(20);

        $validatorPlan = Validator::make($input, (new $this->modal)->fieldSetValidate());
        if ($validatorPlan->fails()) {
            $rowErrors->push([$validatorPlan->messages()]);
        }

        $plan = $this->savePlan($input);
        $this->savePlanOpiton($input, $plan->id);

        //prepare for send mail
        $campaign_name = $campaign['web_name'];
        $url = '<a href="' . url('plan/phone/confirm/' . $input['token']) . '">' . url('plan/phone/confirm/' . $input['token']) . '</a><br>';
        $link = '<a href="' . url('/booking/cancel/' . $input['token']) . '" target="_blank">URL</a>';
        $time = $input['start_time'] . ' - ' . $input['end_time'];

        $var = array(
            $campaign_name,
            $input['store_name'],
            $input['date'],
            $time,
            $url,
            $link,
        );

        $template = 'mail_plan_request_user';

        if(!empty($input['email']))
            Helper::sendMailWithMailTemplate($input['email'], $template, $input['store_id'], $var, $input['user_name'], $input['phone']);

        Helper::sendMailWithMailTemplate(env('MAIL_ADMIN'), $template, $input['store_id'], $var, $input['user_name'], $input['phone']);

        return redirect('booking/notify');
    }

    protected function checkPlan($input)
    {
        $photo_id = Campaign::find($input["campaign_id"])->photo_id;
        $curLane = LanePhoto::getLaneByStoreAndPhoto($input["store_id"], $photo_id)->number;
        $plans = Plan::buildReservationPlan($input["store_id"], $photo_id, $input["date"]);
        $plans = $plans->where('start_time', $input["start_time"])->where('end_time', $input["end_time"])->get()->toarray();
        return $curLane <= count($plans);
    }

    protected function checkTime($input){
        if ($this->isLogin()){
            return false;
        }

        $now = Helper::getDateTimeWithCarbon()::now();
        $today = Helper::getDateTimeWithCarbon()::today();
        $numberDate = $now->lt($today->copy()->hour(BookCalendarController::END_WORKING_HOUR)) ? 3 : 4 ;
        $dateOrder = Helper::getDateTimeWithCarbon()::parse($input["date"]);
        $maxDate = $today->addDay($numberDate);

        return $dateOrder->lt($maxDate);
    }

    protected function checkPlanExpired($plan)
    {
        return Helper::getDateTimeWithCarbon()->diffInMinutes($plan['updated_at']) > 1440;
    }

    protected function savePlan($input)
    {
        $customer = $this->getDataCustomer($input['phone']);
        $plan = new Plan();
        $plan->user_id = $input['user_id'];
        $plan->name = $input["name"];
        $plan->store_id = (int)$input["store_id"];
        $plan->campaign_id = (int)$input["campaign_id"];
        $plan->start_time = $input["start_time"];
        $plan->end_time = $input["end_time"];
        $plan->date = $input["date"];
        $plan->token = $input['token'];
        $plan->last_update_by = $input['user_id'];
        $plan->user_name = $input['user_name'];
        $plan->user_phone = $input['phone'];
        $plan->user_email = $input['email'];
        $plan->data_customer = $customer;
        $plan->status = Plan::STATUS_CONFIRM;
        $plan->is_enable = 1;
        $plan->is_deleted = 0;
        $plan->save();

        return $plan;
    }

    protected function getDataCustomer($tel)
    {
        try {
            $customerInfo = (new BaseRequestController())->get(self::URL_API_CUSTOMER . '?tel=' . $tel, self::HEADER_API_CUSTOMER);
            $arrayData = json_decode($customerInfo, true);
            if (isset($arrayData["ok"]) && $arrayData["ok"] == "true") {
                return $customerInfo;
            } else {
                return "{}";
            }
        } catch (\Exception $e) {
        }
        return "{}";
    }

    protected function savePlanOpiton($input, $plan_id)
    {
        $planOption = new PlanCampaignOption();
        $planOption->plan_id = $plan_id;
        $planOption->content = $input["content"];
        $planOption->is_deleted = 0;
        $planOption->save();

        return $planOption;
    }

    protected function saveUser($input)
    {
        $user = new User();
        $user->username = $input['username'];
        $user->password = bcrypt('12345678');
        $user->email = $input['email'];
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->kana_first_name = $input['kana_first_name'];
        $user->kana_last_name = $input['kana_last_name'];
        $user->phone = $input['phone'];
        $user->role_id = $input['role_id'];
        $user->store_id = $input['store_id'];
        $user->save();
        $user->last_update_by = $user->id;
        $user->save();

        // auto create f_id
        $f_number = new FNumber();
        $f_number->name = str_random(20);
        $f_number->memo = 'default';
        $f_number->user_id = $user->id;
        $f_number->save();

        return $user;
    }

    protected function confirm(Request $request, $token)
    {
        $plan = Plan::where('token', $token)->where('status', Plan::STATUS_CONFIRM)->first();

        if (empty($plan)) {
            return abort(404);
        }

        if ($this->checkPlanExpired($plan)) {
            return view('frontend/confirm_expired');
        }

        if ($plan['user_phone'] == $request->phone) {
            return redirect('booking/cancel/' . $token);
        }

        return Redirect::back()->withErrors(['exist' => trans('validation.custom.booking.phone')]);
    }

    protected function phoneConfirm($token)
    {
        $plan = Plan::where('token', $token)->where('status', Plan::STATUS_CONFIRM)->first();

        if (empty($plan)) {
            return abort(404);
        }

        return redirect('/booking/confirm/' . $token);
    }

    protected function infoCancel($token)
    {
        $plan = Plan::where('token', $token)->first();

        if (!$plan) {
            return abort(404);
        }
        return view('frontend/cancel', compact('plan'));
    }

    protected function info($token)
    {
        $plan = Plan::where('token', $token)->first();

        if (!$plan) {
            return abort(404);
        }
        return view('frontend/info', compact('plan'));
    }

    protected function cancel(Request $request, $token)
    {
        $plan = Plan::where('token', $token)->first();
        $plan['status'] = Plan::STATUS_CANCEL;
        $reservationDate = Helper::getDateTimeWithCarbon()::parse($plan['date']);
        $now = Helper::getDateTimeWithCarbon()::now();
        if ($now->gt($reservationDate->subDay()->hour(BookCalendarController::END_WORKING_HOUR))){
            $expiredBooking = true;
            return view('frontend/cancel_notify', compact('expiredBooking'));
        }
        $plan->save();

        //prepare for send mail

        $time = $plan['start_time'] . ' - ' . $plan['end_time'];
        $this->sendMailCancelBooking($plan, $time);

        if ($request->type == 0) {
            return view('frontend/cancel_notify');
        } else {
            $plan_resource = new $this->resource($plan);
            return redirect('calendar/' . $plan_resource['campaign']['code'] . '/' . $plan_resource['store']['code']);
        }
    }

    protected function notify()
    {
        if ($this->status) {
            return view('frontend/notify');
        }

        return abort(404);
    }

    protected function changeStatus($id, $status)
    {
        $plan = $this->modal::find($id);
        $plan->status = $status;
        $plan->save();
        $this->sendMailWithStatus($status, $plan);
    }

    protected function sendMailWithStatus($status, $plan)
    {
        $time = $plan['start_time'] . ' - ' . $plan['end_time'];

        if ($status == Plan::STATUS_CANCEL) {
            $this->sendMailCancelBooking($plan, $time);
        } else {
            $this->sendMailRegisterToCustomer($plan);
        }
    }

    protected function sendMailRegisterToCustomer($plan){
        $time = $plan['start_time'] . ' - ' . $plan['end_time'];
        $campaign = Campaign::find($plan['campaign_id']);
        $store = Store::find($plan['store_id']);
        $url = '<a href="' . url('plan/phone/confirm/' . $plan['token']) . '">' . url('plan/phone/confirm/' . $plan['token']) . '</a><br>';
        $link = '<a href="' . url('/booking/cancel/' . $plan['token']) . '" target="_blank">URL</a>';

        $var = array(
            $campaign['web_name'],
            $store['name'],
            $plan['date'],
            $time,
            $url,
            $link,
        );

        $template = 'mail_plan_request_user';
        if(!empty($plan['user_email']))
            Helper::sendMailWithMailTemplate($plan['user_email'], $template, $plan['store_id'], $var, $plan['user_name'], $plan['user_phone']);
        Helper::sendMailWithMailTemplate(env('MAIL_ADMIN'), $template, $plan['store_id'], $var, $plan['user_name'], $plan['user_phone']);
    }

    protected function sendMailCancelBooking($plan, $time)
    {
        $template = 'mail_cancel_plan';
        $store = Store::where('id', $plan['store_id'])->first();
        $campaign = Campaign::where('id', $plan['campaign_id'])->first();

        if(empty($store) || empty($campaign))
            return abort("404");

        $storeName = $store['name'];
        $campaignName = $campaign['name'];
        $optionData = PlanCampaignOption::where('plan_id', $plan['id'])->orderBy('updated_at', 'desc')->value('content');
        $optionData = json_decode($optionData, true);
        $isHoliday = Helper::checkHoliday($plan['date']);
        $optionText = '<div>';
        if (!empty($optionData)) {
            foreach ($optionData as $option) {
                $optionText .= "&nbsp;&nbsp;<span> " . $option['name'] . "</span><br/>";
                foreach ($option['select'] as $select) {
                    if($select['status'] == 0)
                        continue;
                    $fee_campaign = $isHoliday == 2 ? $select["holiday_price"] : $select["weekday_price"];
                    if(empty($fee_campaign))
                        $fee_campaign = 0;
                    $optionText .= "&nbsp;&nbsp;&nbsp;&nbsp; <span> -" . $select['name'] . " ・ " .$fee_campaign. " ¥</span><br/>";
                }
                $optionText .= "<br/>";
            }
        }

        $optionText .= "</div>";
        $campaignUrl = '<span>' . url('calendar/' . $campaign['code'].'/'.$store['code']) . '</span><br>';
        $var = array(
            $campaignName,
            $storeName,
            $plan['date'],
            $time,
            $optionText,
            $campaignUrl
        );

        if(!empty($plan['user_email']))
            Helper::sendMailWithMailTemplate($plan['user_email'], $template, $plan['store_id'], $var, $plan['user_name'], $plan['user_phone']);

        Helper::sendMailWithMailTemplate(env('MAIL_ADMIN'), $template, $plan['store_id'], $var, $plan['user_name'], $plan['user_phone']);
    }

    public function updateDataCustomer(Request $request)
    {
        $data = $request->all();

        if (sizeof($data) == 0) {
            return $this->sendResponse("No data send", 400);
        }

        $prepareResponse = array();
        foreach ($data as $id) {

            $plan = Plan::find($id);
            $phone = $plan->user_phone;
            $customerInfo = $this->getDataCustomer($phone);
            $plan->data_customer = $customerInfo;
            $plan->save();
            array_push($prepareResponse, array($id => $customerInfo == '{}' ? Lang::get('api.fail') : Lang::get('api.success')));
        }

        return $this->sendResponse($prepareResponse, 200);
    }

    private function getInformation($plan)
    {
        $customer = json_decode($plan['data_customer'], true);
        $campaign = Campaign::where('id', $plan['campaign_id'])->where('is_deleted', false)->first();
        $customer['campaign_name'] = $campaign['name'];
        $plancampaignoption = PlanCampaignOption::where('plan_id', $plan['id'])->where('is_deleted', false)->first();
        $options = json_decode($plancampaignoption['content'], true);

        $customer['plan_date'] = Helper::convertDateFormaṭ($plan['date']);
        $customer['plan_time'] = Helper::convertTimeFormaṭ($plan['start_time']);

        // campaign option select
        if (!empty($options)) {
            $customer['options'] = $options;
        }

        //phone number
        if (isset($customer['new_tel'])) {
            $phone1 = substr($customer['new_tel'], 0, 3);
            $phone2 = substr($customer['new_tel'], 3, 4);
            $phone3 = substr($customer['new_tel'], 7, 4);

            $customer['new_tel'] = '（  ' . $phone1 . '   )    ' . $phone2 . '      ―  ' . $phone3;
        }

        // zip code
        if (isset($customer['zipcode'])) {
            $zip1 = substr($customer['zipcode'], 0, 3);
            $zip2 = substr($customer['zipcode'], 3, 4);

            $customer['zipcode'] = '〒  ' . $zip1 . ' ― ' . $zip2;
        }

        // date
        if (!isset($customer['children'])) {
            return $customer;
        }

        for ($i = 0; $i < count($customer['children']); $i++) {
            if (empty($customer['children'][$i]['birthday'])) {
                continue;
            }

            $customer['children'][$i]['yearold'] = Helper::caculateYearOld($customer['children'][$i]['birthday']);
            $customer['children'][$i]['birthday'] = Helper::convertDateFormaṭ($customer['children'][$i]['birthday']);
        }

        return $customer;
    }

    public function exportPdfMultiple(Request $request)
    {
        $data = $request->all();

        if (!isset($data['list'])) {
            return null;
        }

        $list_id = explode(',', $data['list']);
        $plan = Plan::whereIn('id', $list_id)->where('is_deleted', false);

        if ($plan->count() === 0) {
            return null;
        }

        if (count($list_id) == 1) {
            $plan = $plan->first();
            $infor = $this->getInformation($plan);
            $pdf = PDF::loadView('exports.pdf', $infor);
            $export_file = '予約' . $data['list'] . '.pdf';

            return $pdf->download($export_file);
        }

        $export_file = '予約.zip';

        // Delete if exits export,zip file
        if (file_exists(storage_path($export_file))) {
            unlink(storage_path($export_file));
        }

        $plans = $plan->get()->toarray();
        $zip = Zipper::make(storage_path($export_file));
        $list_file = array();

        foreach ($plans as $plan) {
            $info = $this->getInformation($plan);
            $html = view('exports.pdf')->with($info)->render();
            $pdfFile = storage_path() . '/pdf/plan_' . $plan['id'] . '.pdf';
            PDF::loadHTML($html)->save($pdfFile);
            $zip->add($pdfFile);
            array_push($list_file, $pdfFile);
        }

        $zip->close();

        // Delete all file
        foreach ($list_file as $file) {
            unlink($file);
        }

        return response()->download(storage_path($export_file));
    }

    public function reserveget(Request $request, $limit = 100)
    {
        $plans = Plan::select();
        if ($request->startDate) {
            $plans->whereDate('date', '>=', $request->startDate);
        }
        if ($request->endDate) {
            $plans->whereDate('date', '<=', $request->endDate);
        }

        return $this->sendResponse(
            new $this->collectionPhoto(
                (new $this->modal)->default_query($plans, $request->limit ? $request->limit : $limit)
            ), 200);
    }

    public function customerEdit($token)
    {
        $plan = Plan::where('token', $token)->where('is_deleted', false)->first();

        if (!$plan) {
            return abort(404);
        }

        $plan = new $this->resource($plan);
        $list_data = Plan::getListData($plan->id);
        $options = (DB::table('plan_campaign_option')
            ->where('plan_id', '=', $plan->id)
            ->where('is_deleted', '=', false)
            ->first());
        $options = $options->content;
        $date_type = Helper::checkHoliday($plan->date);

        return view('frontend.plan_edit', compact('plan', 'list_data', 'options', 'date_type'));
    }

    public function customerUpdate(Request $request)
    {
        $input = $request->all();

        if (isset($input['content']) && ($input['content'] != null)) {
            $contents = json_decode($input['content'], true);
            $validate = array();
            $validate['option'] = trans('validation.attributes.select_option');
            if (!(new $this->modal)->CheckValidateOption($contents)) {
                return $this->sendError(404, $validate);
            }
        }

        $object = Plan::where('token', $input['token'])->where('is_deleted', false)->first();
        if (!$object) {
            return abort(404);
        }
        if ($input['time'] != null) {
            $list_data = Plan::getListData($object->id);
            $input['start_time'] = $list_data[$input['time']]['time_start'];
            $input['end_time'] = $list_data[$input['time']]['time_end'];
        }

        if ($object === false) {
            return $this->sendError(404, $this->error_msg_no_object);
        }

        foreach ((new $this->modal)->get_field_table() as $field) {
            if (isset($input[$field])) {
                $object->$field = $input[$field];
            }
        }

        $object->save();

        //save plan option
        if (isset($input['content']) && ($input['content'] != null)) {
            $planOption = PlanCampaignOption::where('plan_id', $object['id'])->first();
            $planOption['content'] = $input['content'];
            $planOption->save();
        }

        //prepare for send mail

        $campaign_name = Campaign::find($object['campaign_id'])->name;
        $store_name = Store::find($object['store_id'])->name;
        $url = '<a href="' . url('plan/phone/confirm/' . $object['token']) . '">' . url('plan/phone/confirm/' . $object['token']) . '</a><br>';
        $link = '<a href="' . url('/booking/cancel/' . $object['token']) . '" target="_blank">URL</a>';
        $update = '<span>Edit plan: </span><a href="' . url('plan/edit/' . $object['token']) . '">' . $campaign_name . '</a><br>';
        $time = $object['start_time'] . ' - ' . $object['end_time'];
        $var = array(
            $campaign_name,
            $store_name,
            $object['date'],
            $time,
            $url,
            $link,
            $update
        );

        $template = 'mail_plan_request_user';

        if(!empty($object['user_email']))
            Helper::sendMailWithMailTemplate($object['user_email'], $template, $object['store_id'], $var, $object['user_name'], $object['user_phone']);

        Helper::sendMailWithMailTemplate(env('MAIL_ADMIN'), $template, $object['store_id'], $var, $object['user_name'], $object['user_phone']);

        return Redirect::back()->withErrors(['処理に成功しました', 'success']);
    }

}
