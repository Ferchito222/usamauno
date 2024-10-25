<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class CareCenter extends Model
{
    protected $table = 'ad_care_centers';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
