<?php

namespace App\Models;

class CampaignMail extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign_mail';
    protected $fillable = ['id', 'type','day','template','action','campaign_id',
        'is_deleted','created_at','updated_at'];
    protected $field_require = ['campaign_id'];
}
