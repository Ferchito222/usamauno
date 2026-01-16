<?php

namespace Sibas\Entities\Vd;

use Illuminate\Database\Eloquent\Model;


class Collection extends Model
{
    protected $table = 'op_vd_collections';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_vd_header_id',
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

    protected $casts = [
        'charged' => 'boolean',
    ];

    public function header()
    {
        return $this->belongsTo(Header::class, 'op_vd_header_id', 'id');
    }
}
