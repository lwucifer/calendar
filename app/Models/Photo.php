<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends BaseModel  {

    /**
     * The database table used by the model.
     *
     * @var string
     * @property string $name
     * @property string $id
     * @property boolean $is_enable
     * @property boolean $is_deleted
     * @property string $cash_id
     * @property string $comment
     * @property string $created_at
     * @property string $updated_at
     * @property string $last_update_by
     *
     */
    protected $table = 'photo';
    protected $fillable = ['id','name', 'is_enable','cash_id',
        'comment', 'last_update_by','is_deleted','created_at','updated_at'];
    protected $field_require = ['name', 'cash_id'];
    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cash()
    {
        return $this->belongsTo('App\Models\Cash');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->hasMany('App\Models\PhotoOption');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\belongToMany
     */
    public function lane()
    {
        return $this->belongsToMany('App\Models\Lane');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function campaign()
    {
        return $this->hasMany('App\Models\Campaign');
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

        $result['name'] = 'required';
        $result['cash_id'] = 'integer|required';

        return $result;
    }
}
