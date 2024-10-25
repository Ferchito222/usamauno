<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'ad_profiles';

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

}
