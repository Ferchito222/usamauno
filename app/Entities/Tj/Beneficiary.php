<?php

namespace Sibas\Entities\Tj;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $table = 'op_tj_beneficiaries';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'coverage',
        'first_name',
        'last_name',
        'mother_last_name',
        'dni',
        'extension',
        'relationship',
        'percentage',
    ];

    protected $hidden = [
        'op_vm_detail_id',
        'created_at',
        'updated_at',
    ];
    
    protected $appends = [
        'full_name',
        'full_dni',
    ];


    public function setFirstNameAttribute($value)
    {
        $this->attributes[ 'first_name' ] = mb_strtoupper($value);
    }


    public function setLastNameAttribute($value)
    {
        $this->attributes[ 'last_name' ] = mb_strtoupper($value);
    }


    public function setMotherLastNameAttribute($value)
    {
        $this->attributes[ 'mother_last_name' ] = mb_strtoupper($value);
    }


    public function setDniAttribute($value)
    {
        $this->attributes[ 'dni' ] = mb_strtoupper($value);
    }


    public function setRelationshipAttribute($value)
    {
        $this->attributes[ 'relationship' ] = mb_strtoupper($value);
    }


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->mother_last_name;
    }


    public function getFullDniAttribute()
    {
        return $this->dni . ' ' . $this->extension;
    }

}
