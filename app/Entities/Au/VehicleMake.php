<?php

namespace Sibas\Entities\Au;

use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{

    protected $table = 'ad_vehicle_makes';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'ad_vehicle_make_id', 'id');
    }

}
