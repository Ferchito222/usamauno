<?php

namespace Sibas\Entities\Eb;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{

    protected $table = 'op_eb_beneficiaries';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_eb_detail_id',
        'coverage',
        'first_name',
        'last_name',
        'mother_last_name',
        'dni',
        'relationship',
        'percentage_credit',
    ];

    protected $appends = [
        'full_name',
    ];


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->mother_last_name;
    }
}
