<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Retailer\Cashier;

class Retailer extends Model
{

    protected $table = 'ad_retailers';

    protected $hidden = [
        'image',
        'created_at',
        'updated_at',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'ad_retailer_users', 'ad_retailer_id', 'ad_user_id');
    }


    public function retailerProducts()
    {
        return $this->hasMany(RetailerProduct::class, 'ad_retailer_id', 'id');
    }


    public function exchangeRate()
    {
        return $this->hasOne(ExchangeRate::class, 'ad_retailer_id', 'id');
    }


    public function cities()
    {
        return $this->belongsToMany(City::class, 'ad_retailer_cities', 'ad_retailer_id', 'ad_city_id');
    }


    public function retailerCities()
    {
        return $this->hasMany(RetailerCity::class, 'ad_retailer_id', 'id');
    }

    public function cashiers()
    {
        return $this->hasMany(Cashier::class, 'ad_retailer_id', 'id');
    }
}
