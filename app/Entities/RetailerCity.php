<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class RetailerCity extends Model
{

    protected $table = 'ad_retailer_cities';

    protected $fillable = [
        'id',
        'ad_retailer_id',
        'ad_city_id',
        'active',
    ];

    protected $hidden = [
        'ad_retailer_id',
        'ad_city_id',
        'created_at',
        'updated_at',
    ];


    public function agencies()
    {
        return $this->belongsToMany(Agency::class, 'ad_retailer_city_agencies', 'ad_retailer_city_id', 'ad_agency_id');
    }


    public function city()
    {
        return $this->belongsTo(City::class, 'ad_city_id', 'id');
    }
}
