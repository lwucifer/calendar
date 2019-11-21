<?php

namespace App\Http\Controllers\API;

use App\Exports\PhotosExport;
use App\Helpers\Helper;
use App\Models\Campaign;
use App\Models\CampaignOption;
use App\Models\Photo;
use App\Models\PhotoOption;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class PhotoController extends BaseController
{
    protected $modal = 'App\Models\Photo';
    protected $resource = 'App\Http\Resources\PhotoResource';
    protected $resourceSelect = 'App\Http\Resources\PhotoSelectResource';

    /** Collection resource for pagination
     *
     * @var string
     */
    private $collection = 'App\Http\Resources\PhotoCollection';

    public function __construct()
    {
        parent::__construct();
    }

    /** Enable number
     *
     * @var int
     */

    /** Search the specified resource in storage by key.
     *
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request)
    {
        $input = $request->all();

        return $this->sendResponse(new $this->collection(
            $this->modal::where(function ($query) use ($input) {
                foreach ($input as $key => $value) {
                    if ($value == null) {
                        continue;
                    }

                    switch ($key) {
                        case 'name':
                            $query->where('name', 'LIKE', '%' . $value . '%');
                            break;
                        default:
                            break;
                    }
                }
            })->orderBy('id', 'desc')->paginate($this->pagination)
        ), 200);
    }

    /** Overwrite display the all resource in storage.
     *
     * @return array
     */
    public function index()
    {
        return $this->sendResponse(new $this->collection($this->modal::where('is_deleted', false)->orderBy('id',
            'desc')->paginate($this->pagination)), 200);
    }

    public function store(Request $request)
    {
        $re = Helper::getRequestJson($request);
        $validator = Validator::make($re, (new $this->modal)->fieldSetValidate());

        if ($validator->fails()) {
            return $this->sendError(404, $validator->errors());
        }

        $photo = $this->savePhoto($re);
        $this->saveOptionPhoto($re['option'], $photo["id"]);
        return $this->sendResponse(new $this->resource($photo), 201);
    }

    public function update($id, Request $request)
    {
        $re = Helper::getRequestJson($request);
        $validator = Validator::make($re, (new $this->modal)->fieldSetValidate());

        if ($validator->fails()) {
            return $this->sendError(404, $validator->errors());
        }

        $object = $this->checkIdRequest($id);

        if ($object === false) {
            return $this->sendError(404, $this->error_msg_no_object);
        }

        $photo = $this->updatePhoto($re, $id);
        $this->updateOptionPhoto($re["option"], $id);
        $this->updateCampaignOption($id, $re["option"]);
        return $this->sendResponse(new $this->resource($photo), 201);

    }

    protected function updateCampaignOption($idPhoto, $content)
    {
        $campaignHasPhoto = Campaign::where('photo_id', $idPhoto)->get();

        if (sizeof($campaignHasPhoto) > 0) {
            foreach ($campaignHasPhoto as $campaign) {
                $listCampaignOption = CampaignOption::where('campaign_id', $campaign['id'])->get();
                if (sizeof($listCampaignOption) > 0) {
                    foreach ($listCampaignOption as $dataCampaignOption) {
                        if (!empty($dataCampaignOption['content']) && $dataCampaignOption['content'] != "[]") {
                            $oldContent = json_decode($dataCampaignOption['content'], true);
                            $newContent = $this->mergePhotoOptionWithCampaignOption($content, $oldContent);
                            $newContent = $this->convertSelectOfCampaignOption($newContent);
                            $this->saveNewContentCampaignOption($dataCampaignOption['id'], $newContent);
                        }
                    }
                }
            }
        }
    }

    protected function convertSelectOfCampaignOption($newContent){
        $contentConvert = $newContent;
        for ($i = 0; $i < sizeof($newContent); $i++) {
            $contentConvert[$i]['select'] = array_values($newContent[$i]['select']);
        }

        return $contentConvert;
    }


    protected function saveNewContentCampaignOption($campaignOptionId, $newContent)
    {
        $now = Carbon::now()->setTimezone('Asia/Tokyo')->second(0)->toDateTimeString();
        $campaignOptionUpdate = CampaignOption::find($campaignOptionId);
        $campaignOptionUpdate->content = json_encode($newContent);
//        $campaignOptionUpdate->weekday_fee = null;
//        $campaignOptionUpdate->holiday_fee = null;
//        $campaignOptionUpdate->weekday_benefits = null;
//        $campaignOptionUpdate->holiday_benefits = null;
//        $campaignOptionUpdate->memo = null;
        $campaignOptionUpdate->save();
    }

    protected function mergePhotoOptionWithCampaignOption($photoContent, $oldCampaignOption)
    {
        //delete old campaign option which not exist in new photo option
        $countCampaignOption = sizeof($oldCampaignOption);
        $countPhotoContent = sizeof($photoContent);
        for($count = 0; $count < $countCampaignOption; $count++){
            if (isset($photoContent[$count])){
                //sync data
                $oldCampaignOption[$count]['type'] = $photoContent[$count]['type'];
                $oldCampaignOption[$count]['require'] = $photoContent[$count]['require'];
                $oldCampaignOption[$count]['name'] = $photoContent[$count]['name'];

                $newCampaignOptionSelect = [];
                $selectOfCampaign = $oldCampaignOption[$count]['select'];
                $selectOfPhoto = $photoContent[$count]['select'];
                for ($i = 0; $i < sizeof($selectOfCampaign); $i++) {
                    $isExist = false;
                    for ($j = 0; $j < sizeof($selectOfPhoto); $j++) {
                        if ($selectOfCampaign[$i]['name'] === $selectOfPhoto[$j]['name']) {
                            $isExist = true;
                            break;
                        }
                    }
                    if (!$isExist) {
                        unset($oldCampaignOption[$count]['select'][$i]);
                    }
                }

                //add new option to campaign option


                for ($i = 0; $i < sizeof($selectOfPhoto); $i++){
                    $isExist = false;
                    for ($j = 0; $j < sizeof($selectOfCampaign); $j++) {
                        if ($selectOfPhoto[$i]['name'] === $selectOfCampaign[$j]['name']){
                            $isExist = true;
//                            $selectOfCampaign[$j]["holiday_price"] = '';
//                            $selectOfCampaign[$j]["weekday_price"] = '';
                            $campaignOptionSame = $selectOfCampaign[$j];
                        }
                    }
                    if ($isExist){
                        if (isset($campaignOptionSame)){
                            array_push($newCampaignOptionSelect, $campaignOptionSame);
                        }
                    }else{
//                        $selectOfPhoto[$i]["holiday_price"] = '';
//                        $selectOfPhoto[$i]["weekday_price"] = '';
                        array_push($newCampaignOptionSelect, $selectOfPhoto[$i]);
                    }
                }
                $oldCampaignOption[$count]['select'] = $newCampaignOptionSelect;

            }else{
                unset($oldCampaignOption[$count]);
            }
        }

        if ($countCampaignOption < $countPhotoContent) {
            for ($i = $countCampaignOption; $i < $countPhotoContent ; $i++){
                $oldCampaignOption[$i] = $photoContent[$i];
            }
        }
        return $oldCampaignOption;
    }

    protected function savePhoto($re)
    {
        $id = Auth::id();
        $photo = new Photo();
        $photo->name = $re["name"];
        $photo->cash_id = (int)$re["cash_id"];
        $photo->comment = $re["comment"];
        $photo->last_update_by = $id;
        $photo->is_enable = 1;
        $photo->save();
        return $photo;
    }

    public function updatePhoto($re, $id)
    {
        $idChange = Auth::id();
        $photo = Photo::find($id);
        $photo->name = $re["name"];
        $photo->cash_id = (int)$re["cash_id"];
        $photo->comment = $re["comment"];
        $photo->last_update_by = $idChange;
        $photo->is_enable = 1;
        $photo->save();
        return $photo;
    }

    public function saveOptionPhoto($re, $idPhoto)
    {
        $content = json_encode($re);
        PhotoOption::create([
            'content' => $content,
            'photo_id' => $idPhoto,
        ]);
    }

    public function updateOptionPhoto($re, $idPhoto)
    {
        $content = json_encode($re);
        $photoOption = PhotoOption::where("photo_id", $idPhoto)->first();
        $photoOption->content = $content;
        $photoOption->save();
    }

    public function list()
    {
        return $this->sendResponse($this->resource::collection($this->modal::where('is_enable', true)->where('is_deleted', false)->get()), 200);
    }

    public function listSelect()
    {
        return $this->sendResponse($this->resourceSelect::collection($this->modal::where('is_enable', true)->where('is_deleted', false)->get()), 200);
    }

    public function exports()
    {
        return (new PhotosExport)->download($this->getExportFileName('photo'));
    }

    public function import(Request $request)
    {
        $user_id = Auth::user()->id;
        try {
            $data = \GuzzleHttp\json_decode($request->csv);
            if (count($data) > 1) {
                $csv_data = array_slice($data, 1);
                $rowSuccess = collect();
                $rowErrors = collect();
                foreach ($csv_data as $index => $row) {
                    $photoRow = $this->convertRowToUser($row);
                    $checked = $this->isUpdateOrCreate($row);
                    $rules = (new $this->modal)->fieldSetValidate($row[0]);
                    $validatorUser = Validator::make($photoRow, $rules);
                    if ($validatorUser->fails()) {
                        $rowErrors->push(['index' => $index, 'message' => $validatorUser->messages()]);
                        continue;
                    }
                    DB::beginTransaction();
                    $photo = $checked === 0 ? Photo::find($row[0]) : new Photo();
                    try {
                        $photo->name = $photoRow['name'];
                        $photo->cash_id = $photoRow['cash_id'];
                        $photo->comment = $photoRow['comment'];
                        $photo->is_enable = $photoRow['is_enable'] ? $photoRow['is_enable'] : 1;
                        $photo->last_update_by = $user_id;
                        $photo->save();

                        DB::commit();
                        $rowSuccess->push(['index' => $index]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        $rowErrors->push(['index' => $index, 'message' => $e->getMessage()]);
                    }

                }
                // redirect(Request::url());
            } else {
                return $this->sendError(trans('validation.custom.import.no_data'));
            }

            return $this->sendResponse([
                'row' => count($csv_data),
                'rowSuccess' => $rowSuccess,
                'rowError' => $rowErrors
            ], 200);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param $row
     *
     * @return mixed
     */
    protected function convertRowToUser($row)
    {
        return $this->convertRowToModal([
            'id',
            'name',
            'cash_id',
            'comment',
            'is_enable'
        ], $row);
    }
}
