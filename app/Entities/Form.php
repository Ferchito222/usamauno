<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'ad_forms';

    protected $hidden = [
        'ad_retailer_product_id',
        'created_at',
        'updated_at',
    ];
}
