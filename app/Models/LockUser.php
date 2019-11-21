<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LockUser extends Model
{
    const TYPE_LOGIN_FAIL = 1;
    protected $fillable = ['id', 'user_id', 'type', 'is_locked'];


}
