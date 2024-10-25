<?php

use Sibas\Entities\Profile;

class ProfileTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Profile();
    }

    protected function getData()
    {
        $data = [];

        $profiles = config('base.profiles');

        foreach ($profiles as $key => $profile) {
            $data[] = [
                'name' => $profile,
                'slug' => $key,
            ];
        }

        return $data;
    }
}
