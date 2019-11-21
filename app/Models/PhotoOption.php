<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PhotoOption extends BaseModel  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'photo_options';

    protected $fillable = ['id','content', 'photo_id','is_deleted','created_at','updated_at'];
    protected $field_require = ['content', 'photo_id'];

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function photo()
    {
        return $this->belongsTo('App\Models\Photo');
    }

}
