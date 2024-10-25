<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'ad_user_permissions';

    protected $fillable = [
        'id',
        'ad_user_id',
        'ad_permission_id',
        'active',
    ];
}
