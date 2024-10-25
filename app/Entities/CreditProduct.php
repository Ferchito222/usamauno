<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class CreditProduct extends Model
{

    protected $table = 'ad_credit_products';

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $hidden = [
        'ad_retailer_product_id',
        'created_at',
        'updated_at',
    ];

}
