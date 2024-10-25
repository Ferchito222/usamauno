<?php

namespace Sibas\Repositories\Au;

use Illuminate\Support\Collection;
use Sibas\Entities\Au\VehicleType;
use Sibas\Repositories\BaseRepository;

class VehicleTypeRepository extends BaseRepository
{

    /**
     * @return Collection
     */
    public function getVehicleType()
    {
        return VehicleType::with('category')->where('active', true)->orderBy('vehicle', 'ASC')->get();
        /**
        $selectOption = $this->getSelectOption();
        $model = VehicleType::select('id', 'vehicle as name', 'active')
                ->with('category')->where('active', true)->orderBy('vehicle', 'ASC')->get();
        
        $model = $selectOption->merge($model->toArray());
        
        return $model;/**/
    }


    public function vehicleTypes()
    {
        return VehicleType::select('id', 'vehicle as name')->where('active', true)->orderBy('vehicle', 'ASC')->get();
    }
}