<?php

use Sibas\Entities\Email;

class EmailTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Email();
    }

    protected function getData()
    {
        $data = [
            [
                'email' => 'mmamani@coboser.com',
                'name'  => 'Miguel Mamani',
            ],
            [
                'email' => 'djmiguelarango@gmail.com',
                'name'  => 'Miguel Angel',
            ],
            [
                'email' => 'cchalco@coboser.com',
                'name'  => 'Carlos Chalco',
            ],
        ];

        return $data;
    }
}
