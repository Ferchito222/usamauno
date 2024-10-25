<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    protected $table = 'op_accounts';

    public $incrementing = false;

    protected $casts = [
        'main_account' => 'boolean',
    ];

    protected $fillable = [
        'id',
        'op_client_id',
        'number',
        'type',
        'main_account',
        'active',
    ];
}
