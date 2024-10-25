<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $table = 'ad_goals';

    protected $fillable = [
        'ad_retailer_product_id',
        'position',
        'goal',
    ];

    public function retailerProduct()
    {
        return $this->belongsTo(RetailerProduct::class, 'ad_retailer_product_id', 'id');
    }
}
