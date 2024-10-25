<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class CompanyProduct extends Model
{
    protected $table = 'ad_company_products';

    protected $hidden = [
        'ad_company_id',
        'ad_product_id',
        'created_at',
        'updated_at',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'ad_company_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ad_product_id', 'id');
    }

}
