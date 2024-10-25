<?php

namespace Sibas\Entities\Tj;

use Illuminate\Database\Eloquent\Model;


class Collection extends Model
{
    protected $table = 'op_tj_collections';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_tj_header_id',
        'fee_number',
        'fee_date',
        'deadline',
        'fee_amount',
        'charged',
        'transaction_number',
        'transaction_date',
        'transaction_amount',
        'transaction_amount_usd',
        'exchange_rate',
        'currency_fortaleza',
        'reason',
        'send_fortaleza',
        'result_fortaleza',
        'cardNumber',
        'customerName',
        'accountNumber',
        'transactionDate',
        'observation',
        'paymentStatus',
    ];

    
    protected $casts = [
        'charged' => 'boolean',
    ];

    public function header()
    {
        return $this->belongsTo(Header::class, 'op_tj_header_id', 'id');
    }

}
