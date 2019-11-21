<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ip extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ips';
    protected $fillable = ['id','ip_address','manager_id','is_deleted'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo('App\Models\Manager');
    }

    /**
     * @param $addressIP
     * @return mixed
     */
    public static function getAddressIP($addressIP)
    {
        $result = self::all()->first();
        $addressAccessIP = json_decode($result['ip_address']);

        if ($addressAccessIP && (in_array($addressIP, $addressAccessIP->ip) || Auth::id() == $result->manager_id)) {
            return true;
        }

        return false;
    }
}
