<?php namespace App\Models;
use Illuminate\Support\Facades\DB;

class Campaign extends BaseModel  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaigns';
    protected $fillable = ['id','name', 'web_name','time',
        'photo_id','is_display_calendar','is_enable','comment',
        'last_update_by','is_deleted','created_at','updated_at','code','create_by'];
    protected $field_require = ['name', 'web_name','time',
        'photo_id'];

    /**
     * One To One relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function photo()
    {
        return $this->belongsTo('App\Models\Photo');
    }

    /**
     * Get ccndition of manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    protected function getConditionManager($query, $current_id)
    {
        $tmp = User::where('id', '=', $current_id)->first();

        $list_campaign_id = CampaignStore::where('store_id',$tmp->store_id)->where('is_deleted', false )->distinct()
            ->select('campaign_id')->get()->toarray();

        $query->whereIn('id', $list_campaign_id);

        return $query;
    }

    protected function checkIsCreate($current_id)
    {

    }

    /**
     * One To One relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function update_by()
    {
        return $this->belongsTo('App\Models\User');
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

        $result['web_name'] = 'required';
        $result['photo_id'] = 'integer|required';
        $result['time'] = 'integer|required';

        return $result;
    }

    public static function getCampaignOptionRanger($id)
    {
        return DB::table('campaign_option')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_option.campaign_id')
            ->where('campaigns.id', $id)
            ->where('campaign_option.type', 1)
            ->where('campaign_option.is_deleted',false)
            ->first();
    }

    public function search($input, $roleName, $current_id, $pagination)
    {
        if (Role::isUser($roleName)) {
            return [];
        }

        $query = self::where(function ($query) use ($input) {
            foreach ($input as $key => $value) {
                if ($value == null)
                    continue;
                switch ($key) {
                    case 'photo_id':
                        $query = $query->where($key, $value);break;
                    case 'name':
                        $query = $query->where($key, 'LIKE', '%' . $value . '%');
                        $query = $query->orWhere('web_name', 'LIKE', '%' . $value . '%');
                        break;
                    case 'id':
                        $query = $query->where($key, $value);
                        break;
                    case 'code':
                        $query = $query->where('code', $value);
                        break;
                    case 'store_id':
                        $list_id = DB::table('campaign_stores')
                            ->where('store_id', '=', $value)
                            ->get()->toarray();

                        if (!empty($list_id)) {
                            $query = $query->where(function ($query) use ($list_id) {
                                foreach ($list_id as $element) {
                                    $query = $query->orwhere('id', $element->campaign_id);
                                }
                            });
                        } else {
                            // empty search
                            $query = $query->where('id', '-1');
                        }
                        break;
                    default:
                        break;
                }
            }
        });

        if (Role::isManager($roleName)) {
            $query = $this->getConditionManager($query, $current_id);
        }


        return $this->default_query($query, $pagination);
    }

    public static function ischeckExistCampaignWithStore($campaign, $store)
    {
        if (!$campaign || !$store) {
            return false;
        }

        $checkCampaign = Campaign::where('code', $campaign)->where('is_enable', true)->where('is_deleted', false)->count();
        $checkStore = Store::where('code', $store)->where('is_enable', true)->where('is_deleted', false)->count();

        if ($checkCampaign == 0 || $checkStore == 0) {
            return false;
        }

        $campaignId = Campaign::where('code', $campaign)->value('id');
        $storeId = Store::where('code', $store)->value('id');
        $existCampaignWithStore = CampaignStore::where('campaign_id', $campaignId)->where('store_id', $storeId)->count();

        return $existCampaignWithStore > 0;
    }

    public static function getCampaignOption($campaignCode) {
        return DB::table('campaign_option')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_option.campaign_id')
            ->where('campaigns.code', $campaignCode)
            ->where('campaign_option.is_deleted',false)
            ->get()->toArray();
    }

    public static function getCampaignBaseFee($options) {
        foreach ($options as $op){
            if($op->type == 1){
                $ret['holiday_fee'] = $op->holiday_fee;
                $ret['weekday_fee'] = $op->weekday_fee;
                return $ret;
            }
        }

        return null;
    }
}
