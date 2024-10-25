<?php

use Sibas\Entities\Activity;

class ActivityTableSeeder extends BaseSeeder
{
    protected function getModel()
    {
        return new Activity();
    }

    protected function getData()
    {
        $category = 'AGRICULTURA Y GANADERIA';

        $activities = [
            0 => 'A-CULTIVO De CEREALES',
            1 => 'A-CULTIVO De OLEAGINOSAS',
            2 => 'A-CULTIVO De PLANTAS PARA LA OBTENCION De FIBRAS',
            3 => 'A-CULTIVO De FLORES Y PLANTAS ORNAMENTALES',
        ];

        $data = [];

        foreach ($activities as $activity) {
            $data[] = [
                'category'   => $category,
                'occupation' => $activity,
                'code'       => mt_rand(1000, 9999)
            ];
        }

        return $data;
    }
}
