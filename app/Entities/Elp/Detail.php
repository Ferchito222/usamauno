<?php

namespace Sibas\Entities\Elp;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Client;
use Sibas\Entities\Retailer\Cashier;

class Detail extends Model
{

    protected $table = 'op_elp_details';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_elp_header_id',
        'client_eco',
        'op_client_id',
        'age',
        'reference_id',
        'reference_other',
        'currency',
        'premium',
        'term',
        'type_term',
        'account_type',
        'charge',
        'charge_response',
        'charge_period',
        'operation_number',
        'operation_currency',
        'voucher',
    ];

    protected $appends = [
        'completed_client',
    ];

    protected $casts = [
        'client_eco' => 'boolean',
        'charge'     => 'boolean',
    ];


    public function header()
    {
        return $this->belongsTo(Header::class, 'op_elp_header_id', 'id');
    }


    public function reference()
    {
        return $this->belongsTo(Cashier::class, 'reference_id', 'id');
    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'op_client_id', 'id');
    }


    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class, 'op_elp_detail_id', 'id');
    }


    public function setReferenceOtherAttribute($value)
    {
        $this->attributes['reference_other'] = mb_strtoupper($value);
    }


    public function setChargeResponseAttribute($value)
    {
        $this->attributes['charge_response'] = mb_strtoupper($value);
    }


    public function setChargePeriodAttribute($value)
    {
        $this->attributes['charge_period'] = mb_strtoupper($value);
    }


    public function getCompletedClientAttribute()
    {
        if (empty( $this->client->gender ) || empty( $this->client->civil_status ) || empty( $this->client->document_type ) || empty( $this->client->country ) || empty( $this->client->place_residence ) || empty( $this->client->locality ) || empty( $this->client->home_address ) || empty( $this->client->home_number ) || empty( $this->client->avenue_street ) || empty( $this->client->occupation_description )) {
            return false;
        }

        return true;
    }
}
