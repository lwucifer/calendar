<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'selections';

    /**
     * One to One relation
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign');
    }
}
