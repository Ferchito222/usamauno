<?php

namespace Sibas\Entities\Elp;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{

    protected $table = 'op_elp_collections';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_elp_header_id',
        'fee_number',
        'fee_date',
        'deadline',
        'fee_amount',
        'charged',
        'transaction_number',
        'transaction_date',
        'transaction_amount',
        'reason',
    ];

    protected $dates = [
        'transaction_date',
    ];

    protected $casts = [
        'charged' => 'boolean',
    ];

    public function header()
    {
        return $this->belongsTo(Header::class, 'op_elp_header_id', 'id');
    }
}
