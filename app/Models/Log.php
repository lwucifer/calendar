<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    const FAIL_STATUS = '0';
    const COMPLETE_STATUS = '1';
    const LOGIN_TITLE = 'title';

    protected $fillable = [
        'id', 'user_id', 'email', 'title', 'note', 'status','created_at', 'updated_at'
    ];

    public function scopeNumberLoginFail($query, $email){
        return $query->where('email', $email)
            ->where('title', Log::LOGIN_TITLE)
            ->where('status', Log::FAIL_STATUS)
            ->count();
    }
}
