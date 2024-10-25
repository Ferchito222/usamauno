<?php

namespace Sibas\Entities;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Sibas\Entities\De\Header;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ad_users';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'username_old',
        'password',
        'full_name',
        'email',
        'email_old',
        'dni',
        'code',
        'position',
        'firm',
        'ad_user_target_id',
        'phone_number',
        'signature',
        'password_change',
        'password_date',
        'attempt',
        'ad_city_id',
        'ad_agency_id',
        'additional_agencies',
        'ad_user_type_id',
        'active',
        'remember_token',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'ad_user_target_id',
        'ad_city_id',
        'ad_agency_id',
        'ad_user_type_id',
        'locked',
        'locked_date',
        'token',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'password_change'     => 'boolean',
        'additional_agencies' => 'array',
        'active'              => 'boolean',
    ];

    protected $with = [
        'retailerUser.retailer.retailerCities.agencies',
        'retailerUser',
        'type',
        'profile',
        'city',
        'agency',
        'permissions',
    ];

    protected $appends = [
        'idc',
        'companies',
        'total_external_request',
    ];

    protected $dates = [
        'password_date',
        'locked_date',
    ];


    public function retailerUser()
    {
        return $this->hasOne(RetailerUser::class, 'ad_user_id', 'id');
    }


    public function type()
    {
        return $this->belongsTo(UserType::class, 'ad_user_type_id', 'id');
    }


    public function profile()
    {
        return $this->belongsToMany(Profile::class, 'ad_user_profiles', 'ad_user_id', 'ad_profile_id');
    }


    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'ad_user_id', 'id');
    }


    public function city()
    {
        return $this->belongsTo(City::class, 'ad_city_id', 'id');
    }


    public function agency()
    {
        return $this->belongsTo(Agency::class, 'ad_agency_id', 'id');
    }


    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'ad_user_permissions', 'ad_user_id', 'ad_permission_id')
            ->wherePivot('active', true);
    }


    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class, 'ad_user_id', 'id');
    }


    public function target()
    {
        return $this->belongsTo(UserTarget::class, 'ad_user_target_id', 'id');
    }


    public function assignment()
    {
        return $this->hasOne(UserAssignment::class, 'ad_user_id', 'id');
    }


    public function headersDe()
    {
        return $this->hasMany(Header::class, 'ad_user_id', 'id');
    }


    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = mb_strtoupper($value);
    }


    public function getCompaniesAttribute()
    {
        $companies = collect([]);

        if ($this->retailerUser instanceof RetailerUser) {
            $retailerUser = $this->retailerUser;
            $items        = $retailerUser->companies;

            if (! is_null($items)) {
                $companies = Company::whereIn('id', $items)->get();
            }
        }

        return collect($companies);
    }


    public function getTotalExternalRequestAttribute()
    {
        $total = Header::where('type', 'Q')
            ->whereHas('guest', function ($q) {
                $q->where('ad_city_id', $this->ad_city_id);
            })->count();

        return $total;
    }

    public function getIdcAttribute()
    {
        return encode($this->id);
    }
}