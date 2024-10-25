<?php

use Sibas\Entities\RetailerProductCoverage;

class RetailerProductCoverageTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new RetailerProductCoverage();
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

        $coverages = $this->getModelData('Coverage');

        foreach ($coverages as $key => $coverage) {
            if ($coverage->slug != 'BC') {
                $data[] = [
                    'ad_retailer_product_id' => $rp_id,
                    'ad_coverage_id'         => $coverage->id,
                    'detail'                 => $key + 1,
                    'active'                 => true,
                ];
            }
        }

        return $data;
    }
}
