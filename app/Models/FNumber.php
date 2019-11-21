<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FNumber extends BaseModel  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'f_numbers';

    protected $fillable = ['name','memo','user_id'];

    protected  $field_require = ['name','user_id'];

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\be
     */
    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }

}
