<?php

use Illuminate\Support\Str;
use Sibas\Entities\City;

class CityTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new City();
    }

    protected function getData()
    {
        $cities = [
            [
                'city' => 'La Paz',
                'abbreviation' => 'LP',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Oruro',
                'abbreviation' => 'OR',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Potosi',
                'abbreviation' => 'PO',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Cochabamba',
                'abbreviation' => 'CB',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Chuquisaca',
                'abbreviation' => 'CH',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Tarija',
                'abbreviation' => 'TJ',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Santa Cruz',
                'abbreviation' => 'SC',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Beni',
                'abbreviation' => 'BE',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Pando',
                'abbreviation' => 'PA',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'El Alto',
                'abbreviation' => '',
                'type_ci' => false,
                'type_re' => false,
                'type_de' => true
            ],
            [
                'city' => 'Persona Extranjera',
                'abbreviation' => 'PE',
                'type_ci' => true,
                'type_re' => false,
                'type_de' => false
            ],
        ];

        $data = [];

        foreach ($cities as $city) {
            $data[] = [
                'name'          => $city['city'],
                'abbreviation'  => $city['abbreviation'],
                'type_ci'       => $city['type_ci'],
                'type_re'       => $city['type_re'],
                'type_de'       => $city['type_de'],
                'slug'          => Str::slug($city['city'])
            ];
        }

        return $data;
    }
}
