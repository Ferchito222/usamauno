<?php

namespace Sibas\Entities\Retailer;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\City;
use Sibas\Entities\Retailer;

class Cashier extends Model
{

    protected $table = 'ad_cashiers';

    protected $fillable = [
        'ad_city_id',
        'agency',
        'agency_slug',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];


    public function retailer()
    {
        return $this->belongsTo(Retailer::class, 'ad_retailer_id', 'id');
    }


    public function city()
    {
        return $this->belongsTo(City::class, 'ad_city_id', 'id');
    }
}
