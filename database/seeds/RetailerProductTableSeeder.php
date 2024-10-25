<?php

use Sibas\Entities\RetailerProduct;

class RetailerProductTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new RetailerProduct();
    }

    protected function getData()
    {
        $data = [];

        $retailer_id     = $this->getModelData('Retailer')->first()->id;
        $companyProducts = $this->getModelData('CompanyProduct');

        $id   = date('U');

        foreach ($companyProducts as $key => $companyProduct) {
            $data[] = [
                'id' => $id + $key,
                'ad_retailer_id'        => $retailer_id,
                'ad_company_product_id' => $companyProduct->id,
                'type'        => ($key > 0 ? 'SP' : 'MP'),
                'billing'     => false,
                'provisional_certificate' => false,
                'modality'    => false,
                'facultative' => ($key > 0 ? false : true),
                'ws'          => false,
                'landing'     => '',
                'questions'   => '',
                'active'      => true
            ];
        }

        return $data;
    }
}
