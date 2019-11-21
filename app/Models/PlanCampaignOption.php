<?php

namespace App\Models;

class PlanCampaignOption extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'plan_campaign_option';
    protected $fillable = ['plan_id', 'is_deleted','created_at','updated_at'];
    protected $field_require = ['plan_id', 'content' ];
}
