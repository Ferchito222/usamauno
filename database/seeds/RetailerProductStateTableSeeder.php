<?php

use Sibas\Entities\RetailerProductState;

class RetailerProductStateTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new RetailerProductState();
    }

    protected function getData()
    {
        $data = [];

        $states           = $this->getModelData('State');
        $retailerProducts = $this->getModelData('RetailerProduct');

        foreach ($retailerProducts as $retailerProduct) {
            if ($retailerProduct->type === 'MP') {
                foreach ($states as $state) {
                    array_push($data, [
                        'ad_retailer_product_id' => $retailerProduct->id,
                        'ad_state_id'            => $state->id,
                        'active'                 => true
                    ]);
                }
            }
        }

        return $data;
    }
}
