<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'ad_companies';

    protected $hidden = [
        'image',
        'created_at',
        'updated_at',
    ];

}
