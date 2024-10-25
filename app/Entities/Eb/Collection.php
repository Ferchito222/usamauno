<?php

namespace Sibas\Entities\Eb;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'op_eb_collections';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_eb_header_id',
        'fee_number',
        'fee_date',
        'fee_amount',
        'charged',
        'transaction_number',
        'transaction_date',
        'transaction_amount',
        'reason',
    ];

    protected $casts = [
        'charged' => 'boolean',
    ];

}
