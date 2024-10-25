<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class RetailerProductState extends Model
{

    protected $table = 'ad_retailer_product_states';
    
    public function states()
    {
        return $this->belongsTo(State::class, 'ad_state_id', 'id');
    }

}