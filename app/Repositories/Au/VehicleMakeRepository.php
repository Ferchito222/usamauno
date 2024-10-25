<?php

namespace Sibas\Repositories\Au;

use Illuminate\Support\Collection;
use Sibas\Entities\Au\VehicleMake;
use Sibas\Entities\Au\VehicleModel;
use Sibas\Repositories\BaseRepository;

class VehicleMakeRepository extends BaseRepository
{

    /**
     * @return Collection
     */
    public function getVehicleMakes()
    {
        return VehicleMake::with('models')->where('active', true)->orderBy('make', 'ASC')->get();
        /**
        $selectOption = $this->getSelectOption();
        $makes = VehicleMake::select('id', 'make as name', 'active')
                ->with('models')
                ->where('active', true)->orderBy('make', 'ASC')->get();
        
        $makes = $selectOption->merge($makes->toArray());
        
        return $makes;/**/
    }


    public function vehicleMakes()
    {
        return VehicleMake::select('id', 'make as name')->with('models')->where('active', true)->orderBy('make',
            'ASC')->get();
    }


    public function vehicleModels()
    {
        return VehicleModel::select('id', 'model as name')->where([
            'active' => true,
        ])->orderBy('model', 'ASC')->get();
    }

}