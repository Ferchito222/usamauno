<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'ad_user_profiles';

    protected $fillable = [
        'id',
        'ad_user_id',
        'ad_profile_id',
        'active',
    ];
}
