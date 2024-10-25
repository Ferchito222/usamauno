<?php

namespace Sibas\Entities\Eb;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Client;

class Detail extends Model
{

    protected $table = 'op_eb_details';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_eb_header_id',
        'client_eco',
        'op_client_id',
        'age',
        'voucher',
    ];

    protected $casts = [
        'client_eco',
    ];


    public function header()
    {
        return $this->belongsTo(Header::class, 'op_eb_header_id', 'id');
    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'op_client_id', 'id');
    }


    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class, 'op_eb_detail_id', 'id');
    }
}
