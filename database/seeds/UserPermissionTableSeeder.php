<?php

use Sibas\Entities\UserPermission;

class UserPermissionTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new UserPermission();
    }

    protected function getData()
    {
        $data = [];

        $users       = $this->getModelData('User');
        $permissions = $this->getModelData('Permission');

        foreach ($users as $user) {
            if ($user->username == 'emontano') {
                foreach ($permissions as $permission) {
                    if ($permission->slug == 'RU') {
                        array_push($data, [
                            'ad_user_id'       => $user->id,
                            'ad_permission_id' => $permission->id,
                            'active'           => true,
                        ]);

                        break;
                    }

                }

                break;
            }
        }

        return $data;
    }
}
