<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class RetailerSubProduct extends Model
{
    protected $table = 'ad_retailer_subproducts';

    public function companyProduct()
    {
        return $this->belongsTo(CompanyProduct::class, 'ad_company_product_id', 'id');
    }

    public function productCompany()
    {
        return $this->belongsTo(RetailerProduct::class, 'ad_company_product_id', 'ad_company_product_id');
    }

}