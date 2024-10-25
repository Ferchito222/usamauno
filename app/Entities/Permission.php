<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'ad_permissions';

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

}
