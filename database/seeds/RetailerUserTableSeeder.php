<?php

use Sibas\Entities\RetailerUser;

class RetailerUserTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new RetailerUser();
    }

    protected function getData()
    {
        $data = [];

        $retailer = $this->getModelData('Retailer')->first();
        $users    = $this->getModelData('User');

        foreach ($users as $user) {
            $data[] = [
                'ad_retailer_id' => $retailer->id,
                'ad_user_id'     => $user->id,
                'active'         => true
            ];
        }

        return $data;
    }
}
