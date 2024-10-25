<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{

    protected $table = 'ad_exchange_rates';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
