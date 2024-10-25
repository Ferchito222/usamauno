<?php

use Illuminate\Support\Str;
use Sibas\Entities\UserType;

class UserTypeTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new UserType();
    }

    protected function getData()
    {
        $user_types = config('base.user_types');

        $data = [];

        foreach ($user_types as $key => $user_type) {
            $data[] = [
                'name' => $user_type,
                'code' => $key,
                'slug' => Str::slug($user_type)
            ];
        }

        return $data;
    }
}
