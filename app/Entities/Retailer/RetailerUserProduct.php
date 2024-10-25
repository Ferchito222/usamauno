<?php

namespace Sibas\Entities\Retailer;

use Illuminate\Database\Eloquent\Model;

class RetailerUserProduct extends Model
{
    protected $table = 'ad_retailer_user_products';

    protected $fillable = [
        'id',
        'ad_retailer_user_id',
        'ad_product_id',
    ];
}
