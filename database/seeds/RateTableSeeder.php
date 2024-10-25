<?php

use Sibas\Entities\Rate;

class RateTableSeeder extends BaseSeeder
{

    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Rate();
    }

    protected function getData()
    {
        $data = [];

        $data[] = [
            'rate_company'           => 0.5,
            'rate_bank'              => 0.32,
            'rate_final'             => 0.82,
            'ad_retailer_product_id' => $this->getModelData('RetailerProduct')->first()->id,
            'ad_credit_product_id'   => null,
            'ad_coverage_id'         => null
        ];

        return $data;
    }
}
