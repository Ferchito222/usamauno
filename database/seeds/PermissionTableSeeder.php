<?php

use Sibas\Entities\Permission;

class PermissionTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Permission();
    }

    protected function getData()
    {
        $data = [];

        $permissions = config('base.permissions');

        foreach ($permissions as $key => $permission) {
            array_push($data, [
                'name' => $permission,
                'slug' => $key,
            ]);
        }

        return $data;
    }
}
