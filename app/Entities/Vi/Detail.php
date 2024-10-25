<?php

namespace Sibas\Entities\Vi;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Client;

class Detail extends Model
{

    protected $table = 'op_vi_details';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_client_id',
        'insured_value',
        'currency',
        'client_code',
        'taker_name',
        'taker_dni',
    ];


    public function client()
    {
        return $this->belongsTo(Client::class, 'op_client_id', 'id');
    }


    public function header()
    {
        return $this->belongsTo(Header::class, 'op_vi_header_id', 'id');
    }


    public function response()
    {
        return $this->hasOne(Response::class, 'op_vi_detail_id', 'id');
    }


    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'op_vi_detail_id', 'id');
    }

}
