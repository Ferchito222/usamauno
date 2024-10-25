<?php

use Illuminate\Database\Seeder;
use Sibas\Entities\RetailerSubProduct;

class RetailerSubProductTableSeeder extends BaseSeeder
{

    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new RetailerSubProduct();
    }

    protected function getData()
    {
        $data = [];

        $retailerProducts = $this->getModelData('RetailerProduct');
        $companyProducts  = $this->getModelData('CompanyProduct');

        $data[] = [
            'ad_retailer_product_id' => $retailerProducts->first()->id,
            'ad_company_product_id' => $companyProducts->last()->id,
            'active' => true,
        ];

        return $data;
    }
}
