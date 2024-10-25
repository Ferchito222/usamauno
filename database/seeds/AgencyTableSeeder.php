<?php

use Illuminate\Support\Str;
use Sibas\Entities\Agency;

class AgencyTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Agency();
    }

    protected function getData()
    {
        $agencies = [
            'AG.TUMUSLA(LPZ)',
            'OFICINA CENTRAL(LPZ)',
            'AG.HIPERMAXI LOS PINOS(LPZ)',
            'AG. VILLA FATIMA (LPZ)',
        ];

        $data = [];

        foreach ($agencies as $agency) {
            $data[] = [
                'name' => $agency,
                'code' => mt_rand(10, 99),
                'slug' => Str::slug($agency)
            ];
        }

        return $data;
    }
}
