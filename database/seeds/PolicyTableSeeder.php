<?php

use Sibas\Entities\Policy;

class PolicyTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Policy();
    }

    protected function getData()
    {
        $data = [];

        $policies = [
            [
                'number'         => 1224560000,
                'end_policy'     => 1224562999,
                'date_begin'     => date('Y-m-d'),
                'date_end'       => date('2020-m-d'),
                'currency'       => null,
                'modality'       => false,
                'auto_increment' => true,
                'active'         => true,
            ],
        ];

        $rp_id = null;
        $retailerProducts = $this->getModelData('RetailerProduct');

        foreach ($retailerProducts as $retailerProduct) {
            if ($retailerProduct->type === 'SP') {
                $rp_id = $retailerProduct->id;

                break;
            }
        }

        foreach ($policies as $policy) {
            $policy['ad_retailer_product_id'] = $rp_id;
            $data[] = $policy;
        }

        return $data;
    }
}
