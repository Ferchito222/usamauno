<?php

use Sibas\Entities\Au\VehicleType;

class VehicleTypeTableSeeder extends BaseSeeder
{

    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new VehicleType();
    }


    protected function getData()
    {
        $data = [ ];

        $vehicle_types = config('vehicle.types');

        foreach ($vehicle_types as $vehicle_type) {
            array_push($data, [
                'vehicle'    => $vehicle_type,
                'percentage' => 0,
                'active'     => true,
            ]);
        }

        return $data;
    }
}
