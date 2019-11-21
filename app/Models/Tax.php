<?php namespace App\Models;

class Tax extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'taxs';
    protected $fillable = ['id','start_date_use','tax_percent','manager_id','is_deleted'];
}
