<?php

namespace Sibas\Entities\Vi;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $table = 'op_vi_responses';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'response',
        'observation',
    ];

}
