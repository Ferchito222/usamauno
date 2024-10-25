<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $table = 'ad_guests';


    public function city()
    {
        return $this->belongsTo(City::class, 'ad_city_id', 'id');
    }


    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = mb_strtoupper($value);
    }


    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = mb_strtoupper($value);
    }
}
