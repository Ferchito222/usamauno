<?php

use Sibas\Entities\ExchangeRate;

class ExchangeRateTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new ExchangeRate();
    }

    protected function getData()
    {
        $data = [];

        $retailer = $this->getModelData('Retailer')->first();

        $data[] = [
            'ad_retailer_id' => $retailer->id,
            'usd_value'      => 1,
            'bs_value'       => 6.86,
        ];

        return $data;
    }
}
