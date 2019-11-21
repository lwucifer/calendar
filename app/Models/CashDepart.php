<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashDepart extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cash_departs';
    protected $fillable = ['id','name','manager_id','is_deleted','created_at','updated_at'];

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function photo()
    {
        return $this->hasMany('App\Models\Photo');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo('App\Models\Manager');
    }

}
