<?php

use Sibas\Entities\Au\VehicleMake;

class VehicleMakeTableSeeder extends BaseSeeder
{

    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new VehicleMake();
    }


    protected function getData()
    {
        $data = [ ];

        $vehicle_makes = config('vehicle.makes');

        foreach ($vehicle_makes as $make => $vehicle_make) {
            array_push($data, [
                'make'   => $make,
                'active' => true,
            ]);
        }

        return $data;
    }
}
