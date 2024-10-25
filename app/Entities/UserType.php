<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'ad_user_types';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
