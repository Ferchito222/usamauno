<?php

namespace Sibas\Entities\Vd;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Client;
use Sibas\Entities\Referred;

class Detail extends Model
{

    protected $table = 'op_vd_details';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_client_id',
        'insured_value',
        'currency',
        'client_code',
        'taker_name',
        'taker_dni',
        'account_number',
        'reference',
        'card_number',
        'card_crypt',
        'holder',
        'signature',
        'expiration_card_year',
        'expiration_card_month',
    ];

    protected $appends = [
        'completed',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'op_client_id', 'id');
    }

    public function header()
    {
        return $this->belongsTo(Header::class, 'op_vd_header_id', 'id');
    }

    public function response()
    {
        return $this->hasOne(Response::class, 'op_vd_detail_id', 'id');
    }

    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class, 'op_vd_detail_id', 'id');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'op_vd_detail_id', 'id');
    }

    public function referred()
    {
        return $this->hasOne(Referred::class, 'id', 'reference');
    }

    public function getCompletedAttribute()
    {
        /*if(empty($this->client->workplace)){
            return false;
        }else*/
            return true;
    }
}