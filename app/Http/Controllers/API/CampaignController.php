<?php

namespace App\Http\Controllers\API;

use App\Exports\CampaignsExport;
use App\Models\CampaignStore;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\Campaign;

/**
 * Class CampaignController
 * @package App\Http\Controllers\API
 */
class CampaignController extends BaseController
{
    /** Overwrite this modal
     *
     * @var string
     */
    protected $modal = 'App\Models\Campaign';

    /** Overwrite this resource
     *
     * @var string
     */
    protected $resource = 'App\Http\Resources\CampaignResource';
    protected $resourceAdminCampaign = 'App\Http\Resources\AdminCampaignListResource';
    protected $resourceSelect = 'App\Http\Resources\CampaignSelectResource';

    /** Collection resource for pagination
     *
     * @var string
     */
    private $collection = 'App\Http\Resources\CampaignCollection';
    private $collectionAdminList = 'App\Http\Resources\CampaignAdminResourceCollection';

    /** Search the specified resource in storage by key.
     *
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        return $this->sendResponse(new $this->collectionAdminList(
            (new $this->modal)->search($request->all(), $this->userInfo['role']['name'],
                $this->getUserLogin('id'), $this->pagination)
        ), 200);
    }

    /** Overwrite display the all resource in storage.
     * @return array
     */
    public function index()
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        $query = $this->modal::select();

        if ($this->isManger()) {
            $query = $this->modal::getConditionManager($query, $this->getUserLogin('id'));
        }

        return $this->sendResponse(
            new $this->collectionAdminList((new $this->modal)->default_query($query, $this->pagination)), 200
        );
    }

    /** Overwrite Create the specified resource in storage.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        $input = $request->all();
        $validator = Validator::make($input, (new $this->modal)->fieldSetValidate());

        if ($validator->fails()) {
            return $this->sendError(404, $validator->errors());
        }

        $object = $this->modal::create($input)->toArray();
        $field = $this->modal::find($object['id']);

        //save store
        if (isset($input['store']) && ($input['store'] != null)) {
            (new $this->modal)->create_foreign_data($input['store'], $object['id'], 'App\Models\CampaignStore',
                'campaign_id', 'store_id');
        }

        //save mail
        if (isset($input['mail']) && ($input['mail'] != null)) {
            (new $this->modal)->create_relation_data($input['mail'], $object['id'], 'App\Models\CampaignMail',
                'campaign_id');
        }

        //save option
        if (isset($input['option']) && ($input['option'] != null)) {
            (new $this->modal)->create_relation_data($input['option'], $object['id'], 'App\Models\CampaignOption',
                'campaign_id');
        }

        return $this->sendResponse(new $this->resource($field), 201);
    }

    /**
     * Overwrite Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function update($id, Request $request)
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        $input = $request->all();
        $validator = Validator::make($input, (new $this->modal)->fieldSetValidate());

        if ($validator->fails()) {
            return $this->sendError(404, $validator->errors());
        }

        $object = $this->checkIdRequest($id);

        if ($object === false) {
            return $this->sendError(404, $this->error_msg_no_object);
        }

        if ($this->isManger()) {
            if ($this->getUserLogin('id') != $object['create_by']) {
                return $this->sendError(401, trans('validation.custom.import.no_data'));
            }
        }

        foreach ((new $this->modal)->get_field_table() as $field) {
            if (isset($input[$field])) {
                $object->$field = $input[$field];
            }
        }

        $object->save();

        //$object['campaign_id'] = $object->id;

        //save store
        if (isset($input['store']) && ($input['store'] != null)) {
            (new $this->modal)->update_foreign_data($input['store'], $object['id'], 'App\Models\CampaignStore',
                'campaign_id', 'store_id');
            $this->updatedAtCampaign($object);
        }

        //save mail
        if (isset($input['mail'])) {

            if(($input['mail'] != null)) {
                foreach ($input['mail'] as $key => $i) {
                    $input['mail'][$key]['campaign_id'] = $object->id;
                    $input['mail'][$key]['is_deleted'] = false;
                }
                (new $this->modal)->update_relation_data($input['mail'], $object['id'], 'App\Models\CampaignMail', 'campaign_id');
            }
            else {
                (new $this->modal)->deleteRelationData($object['id'], 'App\Models\CampaignMail', 'campaign_id');
            }
            $this->updatedAtCampaign($object);
        }



        //save option
        if (isset($input['option']) && ($input['option'] != null)) {
            (new $this->modal)->update_relation_data($input['option'], $object['id'], 'App\Models\CampaignOption',
                'campaign_id');
            $this->updatedAtCampaign($object);
        }

        return $this->sendResponse(new $this->resource($object), 201);
    }

    protected function updatedAtCampaign($campaign)
    {
        $campaign->updated_at = Carbon::now()->timestamp;
        $campaign->save();
    }

    public function exports()
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        return (new CampaignsExport())->download($this->getExportFileName('campaign'));
    }

    public function import(Request $request)
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        try {
            $data = \GuzzleHttp\json_decode($request->csv);
            if ($request->count < 9) {
                return $this->sendError(404, trans('validation.custom.import.no_data'));
            }
            $csv_data = array_slice($data, 1);
            $rowSuccess = collect();
            $rowErrors = collect();

            foreach ($csv_data as $index => $row) {
                $campaignRow = $this->convertRowToUser($row);
                $checked = $this->isUpdateOrCreate($row);
                $rules = (new $this->modal)->fieldSetValidate();
                $validatorUser = Validator::make($campaignRow, $rules);
                if ($validatorUser->fails()) {
                    $rowErrors->push(['index' => $index, 'message' => $validatorUser->messages()]);
                    continue;
                }
                DB::beginTransaction();
                $campaign = $checked == 0 ? Campaign::find($row[0]) : new Campaign();
                try {
                    $campaign->name = $campaignRow['name'];
                    $campaign->web_name = $campaignRow['web_name'];
                    $campaign->time = $campaignRow['time'];
                    $campaign->photo_id = $campaignRow['photo_id'];
                    $campaign->is_display_calendar = $campaignRow['is_display_calendar'] ? $campaignRow['is_display_calendar'] : 1;
                    $campaign->comment = $campaignRow['comment'];
                    $campaign->is_enable = $campaignRow['is_enable'] ? $campaignRow['is_enable'] : 1;
                    if ($checked != 0) {
                        $campaign->code = str_random(30);
                    } else {
                        $campaign->code = $campaignRow['code'] ? $campaignRow['code'] : str_random(30);
                    }
                    $campaign->save();

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
            return $this->sendError($e->getMessage());
        }
    }

    public function list()
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        $query = $this->modal::select();

        if ($this->isManger()) {
            $query = $this->modal::getConditionManager($query, $this->getUserLogin('id'));
        }

        return $this->sendResponse(
            $this->resource::collection(
                (new $this->modal)->default_query_list($query)
            ), 200);
    }

    public function listSelect()
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        $query = $this->modal::select();

        if ($this->isManger()) {
            $query = $this->modal::getConditionManager($query, $this->getUserLogin('id'));
        }

        return $this->sendResponse(
            $this->resourceSelect::collection(
                (new $this->modal)->default_query_list($query)
            ), 200);
    }

    public function copy($id, Request $request)
    {
        if ($this->isUser()) {
            return $this->sendError(401, trans('validation.custom.import.no_data'));
        }

        $object = $this->checkIdRequest($id);

        if ($object === false) {
            return $this->sendError(404, $this->error_msg_no_object);
        }

        $newobject = new $this->modal;

        (new $this->modal)->parseData($this->modal, $newobject, $object);
        $newobject->code = str_random(30);
        $ret = $this->modal::create($newobject->toArray());

        // create relation data
        // campaign store
        (new $this->modal)->copy_data('App\Models\CampaignStore', 'campaign_id', $object['id'], $ret['id']);

        // campaign mail
        (new $this->modal)->copy_data('App\Models\CampaignMail', 'campaign_id', $object['id'], $ret['id']);

        // campaign option
        (new $this->modal)->copy_data('App\Models\CampaignOption', 'campaign_id', $object['id'], $ret['id']);


        return $this->sendResponse(new $this->resource($ret), 201);
    }

    public function convertRowToUser($row)
    {
        $campaign = array(
            'id' => $row[0],
            'name' => $row[1],
            'web_name' => $row[2],
            'time' => $row[3],
            'photo_id' => $row[4],
            'is_display_calendar' => $row[5],
            'comment' => $row[6],
            'is_enable' => $row[7],
            'code' => $row[8],
        );
        return $campaign;
    }

    public function getLinkBooking($id)
    {
        $stores = (new CampaignStore())->stores($id);

        return $stores;
    }

}
