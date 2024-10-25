<?php

use Sibas\Entities\CompanyProduct;

class CompanyProductTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new CompanyProduct();
    }

    protected function getData()
    {
        $data = [];

        $products = $this->getModelData('Product');

        foreach ($products as $product) {
            $data[] = [
                'ad_company_id' => $this->getModelData('Company')->first()->id,
                'ad_product_id' => $product->id,
                'active'        => true
            ];
        }

        return $data;
    }
}
