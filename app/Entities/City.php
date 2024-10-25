<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $table = 'ad_cities';

    protected $casts = [
        'type_ci' => 'boolean',
        'type_re' => 'boolean',
        'type_de' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function retailerCities()
    {
        return $this->hasMany(RetailerCity::class, 'ad_city_id', 'id');
    }


    public function careCenters()
    {
        return $this->hasMany(CareCenter::class, 'ad_city_id', 'id');
    }
}