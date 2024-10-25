<?php

use Illuminate\Support\Facades\Hash;
use Sibas\Entities\User;

class UserTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new User();
    }

    protected function getData()
    {
        $data = [
            [
                'id'        => date('U'),
                'username'  => 'admin',
                'password'  => Hash::make('admin123'),
                'full_name' => 'Administrador',
                'email'     => 'admin@coboser.com',
                'position'  => '',
                'phone_number'  => '2123456',
                'signature'     => '',
                'password_change'   => true,
                'password_date'     => date('Y-m-d H:i:s'),
                'attempt'           => 0,
                'ad_city_id'        => null,
                'ad_agency_id'      => null,
                'ad_user_type_id'   => 1,
                'active'            => true
            ],
            /*[
                'id'        => date('U') + 1,
                'username'  => 'emontano',
                'password'  => Hash::make('emontano123'),
                'full_name' => 'Ernesto Montaño',
                'email'     => 'emontano@sudseguros.com',
                'position'  => '',
                'phone_number'  => '2987654',
                'signature'     => '',
                'password_change'   => true,
                'password_date'     => date('Y-m-d H:i:s'),
                'attempt'           => 0,
                'ad_city_id'        => null,
                'ad_agency_id'      => null,
                'ad_user_type_id'   => 2,
                'active'            => true
            ],
            [
                'id'        => date('U') + 2,
                'username'  => 'aperez',
                'password'  => Hash::make('aperez123'),
                'full_name' => 'Armando Perez',
                'email'     => 'cchalco@coboser.com',
                'position'  => '',
                'phone_number'  => '2444555',
                'signature'     => '',
                'password_change'   => true,
                'password_date'     => date('Y-m-d H:i:s'),
                'attempt'           => 0,
                'ad_city_id'        => null,
                'ad_agency_id'      => null,
                'ad_user_type_id'   => 2,
                'active'            => true
            ],*/
        ];

        return $data;
    }
}
