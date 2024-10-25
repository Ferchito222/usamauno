<?php

namespace Sibas\Entities\Au;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Rate as EntitiesRate;
use Sibas\Entities\RetailerProductCategory;

class Rate extends Model
{
    protected $table = 'op_au_rates';

    public function rate()
    {
        return $this->belongsTo(EntitiesRate::class, 'op_rate_id', 'id');
    }

    public function minimumPremium()
    {
        return $this->belongsTo(MinimumPremium::class, 'ad_city_id', 'ad_city_id');
    }
}