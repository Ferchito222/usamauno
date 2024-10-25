<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class RetailerProductCategory extends Model
{

    protected $table = 'ad_retailer_product_categories';

    protected $hidden = [
        'ad_retailer_product_id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'category_name',
    ];


    public function getCategoryNameAttribute()
    {
        return config('base.vehicle_category.' . $this->category);
    }
}
