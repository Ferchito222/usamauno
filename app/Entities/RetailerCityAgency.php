<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class RetailerCityAgency extends Model
{
    protected $table = 'ad_retailer_city_agencies';

    protected $fillable = [
        'id',
        'ad_retailer_city_id',
        'ad_agency_id',
        'active',
    ];

    public function retailerCity()
    {
        return $this->belongsTo(RetailerCity::class, 'ad_retailer_city_id', 'id');
    }
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'ad_agency_id', 'id');

    }

}
