<?php

use Sibas\Entities\Mc\Certificate;

class McCertificateTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Certificate();
    }

    protected function getData()
    {
        $data = [];

        $retailerProducts = $this->getModelData('RetailerProduct');

        foreach ($retailerProducts as $retailerProduct) {
            if ($retailerProduct->type === 'MP') {
                array_push($data, [
                    'ad_retailer_product_id' => $retailerProduct->id,
                    'type'                   => 'C',
                    'name'                   => 'Formulario de Requisitos de Asegurabilidad Prestatarios SEMBRAR SARTAWI IFD',
                    'active'                 => true
                ]);

                break;
            }
        }

        return $data;
    }
}
