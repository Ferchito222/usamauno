<?php

namespace Sibas\Entities\Vi;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $table = 'op_vi_beneficiaries';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'coverage',
        'first_name',
        'last_name',
        'mother_last_name',
        'dni',
        'relationship',
        'participation',
    ];

}
