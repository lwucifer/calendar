<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Lane;
use App\Models\Store;
use App\Models\LanePhoto;
use Validator;
use DB;
use App\Exports\StoresExport;
use Illuminate\Support\Facades\Auth;

class StoreController extends BaseController
{
	protected $modal = 'App\Models\Store';
	protected $resource = 'App\Http\Resources\StoreResource';
	
	/** Collection resource for pagination
	 *
	 * @var string
	 */
	private $collection = 'App\Http\Resources\StoreCollection';
	private $storeAdminListCollection = 'App\Http\Resources\StoreAdminListCollection';
	private $resourceSelect = 'App\Http\Resources\StoreSelectResource';

	/** Default memo after generate f-id
	 *
	 * @var string
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/** Search the specified resource in storage by key.
	 *
	 * @param Request $request
	 * @return array
	 */
	public function search(Request $request)
	{
		return $this->sendResponse(new $this->storeAdminListCollection(
			(new Store())->search($request->all(), $this->pagination)
		), 200);
	}
	
	/** Overwrite display the all resource in storage.
	 * @return array
	 */
	public function index()
	{
		if ($this->isAdmin()) {
			$query = $this->modal::select();
		} elseif ($this->isUser() || $this->isManger()) {
			$query = $this->modal::select()->where('id', $this->getUserLogin('store_id'));
		}

		return $this->sendResponse(
			new $this->storeAdminListCollection(
				(new $this->modal)->default_query($query, $this->pagination)
			),
			200);
	}
	
	/** Overwrite create the specified resource in storage.
	 *
	 * @param Request $request
	 * @return array
	 */
	public function store(Request $request)
	{
		$input = $request->all();
		$validator = Validator::make($input, (new $this->modal)->fieldSetValidate());

		$input['last_update_by'] = Auth::id();
		
		if ($validator->fails()) {
			return $this->sendError(404, $validator->errors());
		}

		// Validate time of lane in range time of store
        if(!(new $this->modal)->checkValidationTimeLane($input)){
            $errorTime = array();
            $errorTime['timer'] = trans('validation.attributes.time_lane');
            return $this->sendError(404, $errorTime);
        }

		if(isset($input['lanes']))
        {
            foreach ($input['lanes'] as $lane) {
                $validator = Validator::make($lane, (new Lane)->fieldSetValidate());

                if ($validator->fails()) {
                    return $this->sendError(404, $validator->errors());
                }
            }

            // validate one photo only one lane
            if(!(new $this->modal)->CheckValidationPhotoLane($input['lanes'])){
                $errorPhoto = array();
                $errorPhoto['lane'] = trans('validation.attributes.lane_photo');
                return $this->sendError(404, $errorPhoto);
            }
        }

		$object = $this->modal::create($input)->toArray();
		
		$field = $this->modal::find($object['id']);
		
		//save lane
		if (!isset($input['lanes'])) {
			return $this->sendResponse(new $this->resource($field), 201);
		}

		foreach ($input['lanes'] as $lane) {

			$lane['store_id'] = $object['id'];
			
			$created = Lane::create($lane)->toArray();
			
			if (isset($lane['selected_photo'])) {
				foreach ($lane['selected_photo'] as $photo) {
					$lanephoto = new LanePhoto();
					$lanephoto->lane_id = $created['id'];
					$lanephoto->photo_id = $photo['id'];
					$lanephoto->save();
				}
			}
		}
		
		return $this->sendResponse(new $this->resource($field), 201);
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function update($id, Request $request)
	{
		$input = $request->all();

        $validator = Validator::make($input, (new $this->modal)->fieldSetValidate($id));

		if ($validator->fails()) {
			return $this->sendError(404, $validator->errors());
		}

		$input['last_update_by'] = Auth::id();

		$object = $this->checkIdRequest($id);

		if ($object === false) {
			return $this->sendError(404, $this->error_msg_no_object);
		}

        // Validate time of lane in range time of store
        if(!(new $this->modal)->checkValidationTimeLane($input)){
            $errorTime = array();
            $errorTime['timer'] = trans('validation.attributes.time_lane');
            return $this->sendError(404, $errorTime);
        }

		$fieldSave = (new $this->modal)->get_field_table();

		foreach ($input as $field => $value) {
            if (in_array($field, $fieldSave)) {
                $object->$field = $value;
            }
        }

		//save lane
		if (!isset($input['lanes'])) {
            $object->save();
			return $this->sendResponse(new $this->resource($object), 201);
		}

        $list_lane = array();

        // validate one photo only one lane
        if(!(new $this->modal)->CheckValidationPhotoLane($input['lanes'])){
            $errorPhoto = array();
            $errorPhoto['lane'] = trans('validation.attributes.lane_photo');
            return $this->sendError(404, $errorPhoto);
        }

		foreach ($input['lanes'] as $lane) {
			// update
			if (!empty($lane['id'])) {
				
				$validator = Validator::make($lane, (new Lane)->fieldSetValidate());
				
				if ($validator->fails()) {
					return $this->sendError(404, $validator->errors());
				}
				
				$templane = Lane::find($lane['id']);
				
				if (empty($templane)) {
					return $this->sendError(404, $this->error_msg_no_object);
				}
				
				foreach ((new Lane)->get_field_table() as $field) {
					if (isset($lane[$field])) {
						$templane->$field = $lane[$field];
					}
				}
				
				$templane->save();
				
				// update lane_photo
				if (isset($lane['selected_photo'])) {
					$list_photo = array();
					
					foreach ($lane['selected_photo'] as $photo) {
						
						// check if record none exits to create
						$search = LanePhoto::where('photo_id', $photo['id'])
							->where('lane_id', $lane['id'])->where('is_deleted', false)->get()->toarray();
						if (empty($search)) {
							$lanephoto = new LanePhoto();
							$lanephoto->lane_id = $lane['id'];
							$lanephoto->photo_id = $photo['id'];
							$lanephoto->save();
							$lanephoto = null;
						}
						array_push($list_photo, $photo['id']);
						
					}
					
					// find and remove old record
					$old_record = LanePhoto::where('lane_id', $lane['id'])->where('is_deleted',
						false)->get()->toarray();

					if (!empty($old_record)) {
						foreach ($old_record as $old) {
							if (!in_array($old['photo_id'], $list_photo)) {
								$lanephoto = LanePhoto::find($old['id']);
								$lanephoto->is_deleted = true;
								$lanephoto->save();
							}
						}
					}
					
				}
                array_push($list_lane, $lane['id']);
			} else { // create
				$lane['store_id'] = $object['id'];
				
				$validator = Validator::make($lane, (new Lane)->fieldSetValidate());
				
				if ($validator->fails()) {
					return $this->sendError(404, $validator->errors());
				}
				
				$created = Lane::create($lane)->toArray();

				if (isset($lane['selected_photo'])) {
					foreach ($lane['selected_photo'] as $photo) {
						
						$lanephoto = new LanePhoto();
						$lanephoto->lane_id = $created['id'];
						$lanephoto->photo_id = $photo['id'];
						$lanephoto->save();
					}
				}

                array_push($list_lane, $created['id']);
			}

		}

		// find and remove lane
        $old_lane = Lane::where('store_id', $object['id'])->where('is_deleted','=',
            false)->get()->toarray();

        if (!empty($old_lane)) {
            foreach ($old_lane as $id) {
                if (!in_array($id['id'], $list_lane)) {
                    $temp = Lane::find($id['id']);
                    $temp->is_deleted = true;
                    $temp->save();
                }
            }
        }

		// save store data
        $object->save();

		return $this->sendResponse(new $this->resource($object), 201);
	}
	
	public function exports()
	{
		return (new StoresExport)->download($this->getExportFileName('store'));
	}
	
	public function import(Request $request)
	{
		try {
			$data = \GuzzleHttp\json_decode($request->csv);
			if ($request->count < 21) {
				return $this->sendError(404, trans('validation.custom.import.no_data'));
			}
			
			$csv_data = array_slice($data, 1);
			$rowSuccess = collect();
			$rowErrors = collect();
			
			foreach ($csv_data as $index => $row) {
				$storeRow = $this->convertRowToUser($row);
				$checked = $this->isUpdateOrCreate($row);
				$rules = (new $this->modal)->fieldSetValidate($row[0]);
				$validatorUser = Validator::make($storeRow, $rules);
				if ($validatorUser->fails()) {
					$rowErrors->push(['index' => $index, 'message' => $validatorUser->messages()]);
					continue;
				}
				DB::beginTransaction();
				$store = $checked == 0 ? Store::find($row[0]) : new Store();
				try {
					$user_id = Auth::user()->id;
					$store->last_update_by = $user_id;
					$store->name = $storeRow['name'];
					$store->phone = $storeRow['phone'] ? $storeRow['phone'] : null;
					$store->manager_id = $storeRow['manager_id'];
					$store->sign_email = $storeRow['sign_email'];
					$store->comment = $storeRow['comment'];
					$store->weekday_start_time = $storeRow['weekday_start_time'];
					$store->weekday_end_time = $storeRow['weekday_end_time'];
					$store->holiday_start_time = $storeRow['holiday_start_time'];
					$store->holiday_end_time = $storeRow['holiday_end_time'];
					$store->day_off_monday = $storeRow['day_off_monday'] ? $storeRow['day_off_monday'] : 0;
					$store->day_off_tuesday = $storeRow['day_off_tuesday'] ? $storeRow['day_off_tuesday'] : 0;
					$store->day_off_wednesday = $storeRow['day_off_wednesday'] ? $storeRow['day_off_wednesday'] : 0;
					$store->day_off_thursday = $storeRow['day_off_thursday'] ? $storeRow['day_off_thursday'] : 0;
					$store->day_off_friday = $storeRow['day_off_friday'] ? $storeRow['day_off_friday'] : 0;
					$store->day_off_saturday = $storeRow['day_off_saturday'] ? $storeRow['day_off_saturday'] : 0;
					$store->day_off_sunday = $storeRow['day_off_sunday'] ? $storeRow['day_off_sunday'] : 0;
					$store->fixed_days_off = $storeRow['fixed_days_off'];
					$store->fixed_days_on = $storeRow['fixed_days_on'];
					$store->is_enable = $storeRow['is_enable'] ? $storeRow['is_enable'] : 1;
					if ($checked != 0) {
                        $store->code = str_random(30);
                    } else {
                        $store->code = $storeRow['code'] ? $storeRow['code'] : str_random(30);
                    }
					$store->save();
					
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
	
	protected function convertRowToUser($row)
	{
		return $this->convertRowToModal([
			'id',
			'name',
			'phone',
			'manager_id',
			'sign_email',
			'comment',
			'weekday_start_time',
			'weekday_end_time',
			'holiday_start_time',
			'holiday_end_time',
			'day_off_monday',
			'day_off_tuesday',
			'day_off_wednesday',
			'day_off_thursday',
			'day_off_friday',
			'day_off_saturday',
			'day_off_sunday',
			'fixed_days_off',
			'fixed_days_on',
			'is_enable',
			'code'
		], $row);
	}
	
	public function list()
	{
        $query = $this->modal::select();

        if($this->isManger())
        {
            $query = $this->modal::getConditionManager($query, $this->getUserLogin('id'));
        }

		return $this->sendResponse(
			$this->resource::collection(
				(new $this->modal)->default_query_list($query)
			), 200);
	}

	public function listSelect()
	{
        $query = $this->modal::select();

        if($this->isManger())
        {
            $query = $this->modal::getConditionManager($query, $this->getUserLogin('id'));
        }

		return $this->sendResponse(
			$this->resourceSelect::collection(
				(new $this->modal)->default_query_list($query)
			), 200);
	}

	public function listEnable(){

        $query = $this->modal::select();

        if($this->isManger())
        {
            $query = $this->modal::getConditionManager($query, $this->getUserLogin('id'));
        }

        return $this->sendResponse(
            $this->resource::collection(
                (new $this->modal)->default_query_list_enable($query)
            ), 200);
    }
}
