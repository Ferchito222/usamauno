<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'ad_products';

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

}
