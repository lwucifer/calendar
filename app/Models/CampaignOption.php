<?php

namespace App\Models;

class CampaignOption extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign_option';
    protected $fillable = ['id','type', 'start_time','end_time','weekday_fee',
        'holiday_fee','memo','weekday_benefits','holiday_benefits','campaign_id',
        'content','is_deleted','created_at','updated_at'];
    protected $field_require = ['campaign_id', 'type'];
}
