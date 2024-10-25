<?php

use Illuminate\Support\Str;
use Sibas\Entities\Company;

class CompanyTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Company();
    }

    protected function getData()
    {
        $data = [];

        $name = 'Alianza Grupo Asegurador';

        $data[] = [
            'name'    => $name,
            'image'   => 'alianza.jpg',
            'slug'    => Str::slug($name),
            'active'  => true
        ];

        return $data;
    }
}
