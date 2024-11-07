<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Client;

class Detail extends Model
{
    protected $table = 'op_de_details';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_de_header_id',
        'op_client_id',
        'percentage_credit',
        'company_approval',
        'rate',
        'balance',
        'cumulus',
        'refinancing',
        'parallel_credit',
        'amount',
        'approved',
        'rejected',
        'headline',
        'involuntary_unemployment',
        'validated',
        'process',
        'cesantia',
        'ad_retailer_product_id',
        'rate_cesantia',
        'send_fortaleza',
        'detail_operations',
        'date_entry',
    ];

    protected $casts = [
        'approved'                 => 'boolean',
        'rejected'                 => 'boolean',
        'involuntary_unemployment' => 'boolean',
        'validated'                => 'boolean',
        'process'                  => 'boolean',
    ];

    protected $hidden = [
        'op_de_header_id',
        'op_client_id',
        'process',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'idc',
    ];


    public function header()
    {
        return $this->belongsTo(Header::class, 'op_de_header_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'op_client_id', 'id');
    }

    public function response()
    {
        return $this->hasOne(Response::class, 'op_de_detail_id', 'id');
    }

    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class, 'op_de_detail_id', 'id');
    }

    public function facultative()
    {
        return $this->hasOne(Facultative::class, 'op_de_detail_id', 'id');
    }

    public function coverages()
    {
        return $this->hasMany(DetailCoverage::class, 'op_de_detail_id', 'id');
    }

    public function getCompletedAttribute()
    {
        /**
        if(in_array($this->header->creditProduct->slug, ['PMO', 'PLT']))
             return (! is_null($this->beneficiary) ? true : false);
        else
            return true;
        /**/
        return (! is_null($this->beneficiary) ? true : false);
    }


    public function getIdcAttribute()
    {
        return encode($this->id);
    }
}
