<?php

use Sibas\Entities\RetailerProductEmail;

class RetailerProductEmailTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new RetailerProductEmail();
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

        $emails = $this->getModelData('Email');

        foreach ($emails as $key => $email) {
            $data[] = [
                'ad_retailer_product_id' => $rp_id,
                'ad_email_id'            => $email->id,
                'active'                 => true,
            ];
        }

        return $data;
    }

}
