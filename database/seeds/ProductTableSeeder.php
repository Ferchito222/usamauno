<?php

use Illuminate\Support\Str;
use Sibas\Entities\Product;

class ProductTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Product();
    }

    protected function getData()
    {
        $data = [];

        $products = [
            [
                'type' => 'PH',
                'name' => 'Desgravamen',
                'code' => 'de',
                'slug' => Str::slug('Desgravamen')
            ],
            [
                'type' => 'PH',
                'name' => 'Vida',
                'code' => 'vi',
                'slug' => Str::slug('Vida')
            ]
        ];

        foreach ($products as $product) {
            $data[] = $product;
        }

        return $data;
    }
}
