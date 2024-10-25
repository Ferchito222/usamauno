<?php

use Sibas\Entities\ProductParameter;

class ProductParameterTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new ProductParameter();
    }

    protected function getData()
    {
        $data = [];

        $rp_id = null;
        $retailerProducts = $this->getModelData('RetailerProduct');

        foreach ($retailerProducts as $retailerProduct) {
            if ($retailerProduct->type === 'MP') {
                $rp_id = $retailerProduct->id;

                break;
            }
        }

        $parameters = [
            [
                'ad_retailer_product_id' => $rp_id,
                'name'       => 'General',
                'slug'       => 'GE',
                'age_min'    => 18,
                'age_max'    => 71,
                'amount_min' => 0,
                'amount_max' => 0,
                'expiration' => 60,
                'detail'     => 1,
                'old_car'    => 0,
            ],
            [
                'ad_retailer_product_id' => $rp_id,
                'name'       => 'Free Cover',
                'slug'       => 'FC',
                'age_min'    => 18,
                'age_max'    => 71,
                'amount_min' => 1,
                'amount_max' => 35000,
                'expiration' => 0,
                'detail'     => 0,
                'old_car'    => 0,
            ],
            [
                'ad_retailer_product_id' => $rp_id,
                'name'       => 'Afiliación Automática',
                'slug'       => 'AE',
                'age_min'    => 18,
                'age_max'    => 71,
                'amount_min' => 35001,
                'amount_max' => 350000,
                'expiration' => 0,
                'detail'     => 0,
                'old_car'    => 0,
            ],
            [
                'ad_retailer_product_id' => $rp_id,
                'name'       => 'Facultativo',
                'slug'       => 'FA',
                'age_min'    => 18,
                'age_max'    => 71,
                'amount_min' => 350001,
                'amount_max' => 1000000000000000,
                'expiration' => 0,
                'detail'     => 0,
                'old_car'    => 0,
            ]
        ];

        foreach ($parameters as $parameter) {
            $data[] = $parameter;
        }

        return $data;
    }
}
