<?php

namespace Sibas\Entities\De;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Certificate;
use Sibas\Entities\City;
use Sibas\Entities\CreditProduct;
use Sibas\Entities\Guest;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\RetailerCityAgency;
use Sibas\Entities\User;

class Header extends Model
{

    protected $table = 'op_de_headers';

    public $incrementing = false;

    protected $appends = [
        'completed',
        'certificate_number',
        'created_date',
        'days_from_creation',
        'validated',
        'idc',
        'policy_end',
    ];

    protected $casts = [
        'issued'           => 'boolean',
        'canceled'         => 'boolean',
        'facultative'      => 'boolean',
        'facultative_sent' => 'boolean',
        'credit_line'      => 'boolean',
        'approved'         => 'boolean',
        'copy'             => 'integer',
        'read'             => 'boolean',
        'is_new'           => 'boolean',
        'data_records'     => 'array',
    ];

    protected $fillable = [
        'id',
        'ad_user_id',
        'ad_guest_id',
        'ad_retailer_product_id',
        'type',
        'quote_number',
        'policy_number',
        'version_number',
        'ad_policy_id',
        'product_description',
        'operation_number',
        'ad_coverage_id',
        'amount_requested',
        'currency',
        'term',
        'type_term',
        'issued',
        'ad_credit_product_id',
        'movement_type',
        'facultative',
        'copy',
        'read',
        'card_number',
        'ad_certificate_id',
        'file',
        'is_new',
        'data_records',
        'type_of_request',
        'planchado_tasa_fortaleza',
        'ad_retailer_city_agency_id',
    ];

    protected $hidden = [
        'ad_user_id',
        'ad_guest_id',
        'ad_retailer_product_id',
        'ad_coverage_id',
        'ad_credit_product_id',
        'total_premium',
        'approved',
        'rejected',
        'copy',
        'read',
        'ad_certificate_id',
        'updated_at',
    ];

    protected $dates = [
        'date_issue',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function guest()
    {
        return $this->belongsTo(Guest::class, 'ad_guest_id', 'id');
    }


    public function coverage()
    {
        return $this->belongsTo(Coverage::class, 'ad_coverage_id', 'id');
    }


    public function details()
    {
        return $this->hasMany(Detail::class, 'op_de_header_id', 'id');
    }

    public function rejetedCustomers()
    {
        return $this->hasMany(RejectedCustomer::class, 'op_de_header_id', 'id');
    }


    public function cancellation()
    {
        return $this->hasOne(Cancellation::class, 'op_de_header_id', 'id');
    }


    public function coverageWarranty()
    {
        return $this->hasOne(CoverageWarranty::class, 'op_de_header_id', 'id');
    }


    public function creditProduct()
    {
        return $this->belongsTo(CreditProduct::class, 'ad_credit_product_id', 'id');
    }


    public function retailerProduct()
    {
        return $this->belongsTo(RetailerProduct::class, 'ad_retailer_product_id', 'id');
    }

    public function retailerCityAgency()
    {
        return $this->belongsTo(RetailerCityAgency::class, 'ad_retailer_city_agency_id', 'id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'ad_certificate_id', 'id');
    }


    public function placeIssuance()
    {
        return $this->belongsTo(City::class, 'place_issuance', 'id');
    }


    public function creditLine()
    {
        return $this->hasOne(CreditLine::class, 'op_de_header_id', 'id');
    }


    public function getCompletedAttribute()
    {
        $completed = true;

        foreach ($this->details as $detail) {
            if ( (! $detail->completed || $detail->cumulus == 0) || (! is_null($this->ad_guest_id) && ! $detail->validated)) {
                $completed = false;

                break;
            }
        }

        return $completed;
    }


    public function getCertificateNumberAttribute()
    {
        return $this->prefix . '-' . $this->issue_number;
    }


    public function getCreatedDateAttribute()
    {
        return Carbon::createFromTimestamp(strtotime($this->created_at))->format('d/m/Y H:i a');
    }


    public function getDaysFromCreationAttribute()
    {
        $date_now    = Carbon::now();
        $date_create = Carbon::createFromTimestamp(strtotime($this->created_at));

        return $date_now->diffInDays($date_create);
    }

    public function getPolicyEndAttribute()
    {
        $date = date('Y-m-d');
        if($this->type_term == 'D')
            $date = carbon::parse($this->date_issue)->addDays($this->term);

        if($this->type_term == 'M')
            $date = carbon::parse($this->date_issue)->addMonths($this->term);

        if($this->type_term == 'W')
            $date = carbon::parse($this->date_issue)->addWeeks($this->term);

        if($this->type_term == 'Y')
            $date = carbon::parse($this->date_issue)->addYears($this->term);
        
        return $date;
    }


    public function getValidatedAttribute()
    {
        foreach ($this->details as $detail) {
            if (! $detail->validated) {
                return false;
            }
        }

        return true;
    }


    public function getIdcAttribute()
    {
        return encode($this->id);
    }
}
