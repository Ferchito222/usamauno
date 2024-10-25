<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductParameter extends Model
{
    protected $table = 'ad_product_parameters';

    protected $fillable = [
        'id',
        'amount_max_ca',
        'amount_max_cb',
        'year_max_ca',
        'year_max_cb',
    ];

    public function creditProduct()
    {
        return $this->belongsTo(CreditProduct::class, 'ad_credit_product_id', 'id');
    }
}
