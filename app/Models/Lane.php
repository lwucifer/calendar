<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lane extends BaseModel  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lanes';
    protected $fillable = ['id','name', 'order', 'number' ,'weekday_start_time',
        'weekday_end_time','holiday_start_time','holiday_end_time','visit_time',
        'store_id','is_deleted','created_at','updated_at'];
    protected $field_require = [];
    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\belongToMany
     */
    public function photo()
    {
        return $this->belongsToMany('App\Models\Photo');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
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

        $result['weekday_start_time'] = 'after:initial_time|nullable';
        $result['weekday_end_time'] = 'after:initial_time|nullable';
        $result['holiday_start_time'] = 'after:initial_time|nullable';
        $result['holiday_end_time'] = 'after:initial_time|nullable';

        return $result;
    }

    public static function getLaneByStore($store_id)
    {
        return self::where('store_id', '=', $store_id)
            ->where('is_deleted','=', false)->get()->toarray();
    }
}
