<?php

use Sibas\Entities\Au\VehicleModel;

class VehicleModelTableSeeder extends BaseSeeder
{

    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new VehicleModel();
    }


    protected function getData()
    {
        $data = [ ];

        $vehicle_makes = config('vehicle.makes');
        $makes         = $this->getModelData('VehicleMake')->all();

        foreach ($makes as $make) {
            $models = $vehicle_makes[$make->make]['models'];

            foreach ($models as $model) {
                array_push($data, [
                    'ad_vehicle_make_id' => $make->id,
                    'model'              => $model,
                    'active'             => true,
                ]);
            }
        }

        return $data;
    }
}
