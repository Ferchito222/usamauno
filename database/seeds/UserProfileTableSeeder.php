<?php

use Sibas\Entities\UserProfile;

class UserProfileTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new UserProfile();
    }

    protected function getData()
    {
        $data = [];

        $users = $this->getModelData('User');

        $profiles = $this->getModelData('Profile');

        foreach ($users as $user) {
            foreach ($profiles as $profile) {
                switch ($user->username) {
                    case 'emontano':
                        if ($profile->slug === 'SEP') {
                            $data[] = [
                                'ad_user_id'    => $user->id,
                                'ad_profile_id' => $profile->id,
                                'active'        => true,
                            ];
                        }
                        break;
                    case 'aperez':
                        if ($profile->slug === 'COP') {
                            $data[] = [
                                'ad_user_id'    => $user->id,
                                'ad_profile_id' => $profile->id,
                                'active'        => true,
                            ];
                        }
                        break;
                }
            }
        }

        return $data;
    }
}
