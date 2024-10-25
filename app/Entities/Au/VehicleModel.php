<?php

namespace Sibas\Entities\Au;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{

    protected $table = 'ad_vehicle_models';

    protected $hidden = [
        'ad_vehicle_make_id',
        'created_at',
        'updated_at',
    ];

}
