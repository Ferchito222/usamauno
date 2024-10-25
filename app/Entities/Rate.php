<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Au\Increment;
use Sibas\Entities\Au\Rate as AuRate;

class Rate extends Model
{

    protected $table = 'ad_rates';
    
    protected $fillable = [
        'id',
        'active',
        'rate_damage_insurance',
    ];


    public function increments()
    {
        return $this->hasMany(Increment::class, 'ad_rate_id', 'id');
    }


    public function creditProduct()
    {
        return $this->belongsTo(CreditProduct::class, 'ad_credit_product_id', 'id');
    }

    public function auRates()
    {
        return $this->hasMany(AuRate::class, 'ad_rate_id', 'id');
    }
}
