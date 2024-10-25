<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

    protected $table = 'ad_certificates';

    protected $hidden = [
        'ad_retailer_product_id',
        'ad_credit_product_id',
        'created_at',
        'updated_at',
        'active',
    ];


    public function creditProduct()
    {
        return $this->belongsTo(CreditProduct::class, 'ad_credit_product_id', 'id');
    }
}
