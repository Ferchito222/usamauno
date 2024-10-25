<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    public $incrementing = false;

    protected $casts = [
        'data'  => 'array',
        'extra' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }
}
