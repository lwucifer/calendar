<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $table = 'managers';

    /** Check ip is enable
     * @return mixed
     */
    public static function checkEnableIP()
    {
        $result = self::first();
        return $result->enable_ip;
    }
}
