<?php

namespace App\Models;

class CampaignStore extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign_stores';
    protected $fillable = ['campaign_id', 'store_id','is_deleted','created_at','updated_at'];
    protected $field_require = ['campaign_id', 'store_id' ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign');
    }


    public function searchCampaign($condition)
    {
        if ($condition) {
            $campaigns = $this
                ->where('campaign_stores.is_deleted', '=', 0)
                ->whereHas('campaign', function ($query) use ($condition) {
                    $query->where('web_name', 'LIKE', '%' . $condition . '%')
                        ->where('campaigns.is_deleted', '=', 0)->where('campaigns.is_enable', '=', 1)
                        ->where('store.is_deleted', '=', 0)->where('store.is_enable', '=', 1);
                })
                ->orwhereHas('store', function ($query) use ($condition) {
                    $query->where('name', 'LIKE', '%' . $condition . '%')
                        ->where('campaigns.is_deleted', '=', 0)->where('campaigns.is_enable', '=', 1)
                        ->where('store.is_deleted', '=', 0)->where('store.is_enable', '=', 1);
                })
                ->join('campaigns', 'campaign_stores.campaign_id', '=', 'campaigns.id')
                ->join('store', 'campaign_stores.store_id', '=', 'store.id')
                ->select('campaigns.web_name AS campaign_web_name', 'campaigns.code AS campaign_code'
                    , 'store.name AS store_name', 'store.code AS store_code'
                    , 'campaign_stores.*'
                )->orderBy('campaigns.web_name', 'asc')
                ->get()->toArray();
        } else {
            $campaigns = $this
                ->where('campaign_stores.is_deleted', '=', 0)
                ->where('campaigns.is_deleted', '=', 0)->where('campaigns.is_enable', '=', 1)
                ->where('store.is_deleted', '=', 0)->where('store.is_enable', '=', 1)
                ->join('campaigns', 'campaign_stores.campaign_id', '=', 'campaigns.id')
                ->join('store', 'campaign_stores.store_id', '=', 'store.id')
                ->select('campaigns.web_name AS campaign_web_name', 'campaigns.code AS campaign_code'
                    , 'store.name AS store_name', 'store.code AS store_code'
                    , 'campaign_stores.*'
                )->orderBy('campaigns.web_name', 'asc')
                ->get()->toArray();
        }

        return $campaigns;
    }

    public function stores($campaign_id)
    {
        return $this->where('campaign_stores.is_deleted', '=', 0)
            ->whereHas('campaign', function ($query) use ($campaign_id) {
                $query->where('id', '=', $campaign_id)
                    ->where('campaigns.is_deleted', '=', 0)->where('campaigns.is_enable', '=', 1)
                    ->where('store.is_deleted', '=', 0)->where('store.is_enable', '=', 1);
            })
            ->join('campaigns', 'campaign_stores.campaign_id', '=', 'campaigns.id')
            ->join('store', 'campaign_stores.store_id', '=', 'store.id')
            ->select('campaigns.code AS campaign_code', 'store.code AS store_code', 'store.name AS store_name')
            ->orderBy('campaigns.web_name', 'asc')->get()->toArray();
    }
}
