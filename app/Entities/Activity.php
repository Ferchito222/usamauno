<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'ad_activities';

    protected $appends = [
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function getNameAttribute()
    {
        return $this->category . ' - ' . $this->occupation;
    }

}
