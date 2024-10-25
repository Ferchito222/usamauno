<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class RetailerProductEmail extends Model
{
    protected $table = 'ad_retailer_product_emails';

    protected $hidden = [
        'ad_retailer_product_id',
        'ad_email_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

}
