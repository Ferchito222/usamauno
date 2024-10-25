<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    protected $table = 'ad_states';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function retailerProduct()
    {
        return $this->belongsToMany(RetailerProduct::class, 'ad_retailer_product_states', 'ad_state_id',
            'ad_retailer_product_id');
    }

}
