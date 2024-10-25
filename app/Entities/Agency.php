<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Elp\Header;

class Agency extends Model
{
    protected $table = 'ad_agencies';

    protected $fillable = [
        'id',
        'name',
        'code',
        'slug',
        'type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];


    public function retailerCityAgencies()
    {
        return $this->hasMany(RetailerCityAgency::class, 'ad_agency_id', 'id');
    }

    public function headers()
    {
        return $this->hasMany(Header::class, 'ad_agency_id', 'id');
    }

}
