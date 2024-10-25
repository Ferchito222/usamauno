<?php

namespace Sibas\Entities\Eb;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Agency;
use Sibas\Entities\Certificate;
use Sibas\Entities\De\Coverage;
use Sibas\Entities\Retailer\Cashier;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\User;

class Header extends Model
{

    protected $table = 'op_eb_headers';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'ad_user_id',
        'ad_retailer_product_id',
        'type',
        'issue_number',
        'prefix',
        'policy_number',
        'ad_coverage_id',
        'issued',
        'date_issue',
        'approved',
        'ad_agency_id',
        'ad_cashier_id',
        'ad_certificate_id',
    ];

    protected $appends = [
        'completed',
    ];

    protected $casts = [
        'issued'   => 'boolean',
        'canceled' => 'boolean',
        'approved' => 'boolean',
        'renewal'  => 'boolean',
    ];

    protected $dates = [
        'date_issue',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function coverage()
    {
        return $this->belongsTo(Coverage::class, 'ad_coverage_id', 'id');
    }


    public function detail()
    {
        return $this->hasOne(Detail::class, 'op_eb_header_id', 'id');
    }


    public function collection()
    {
        return $this->hasOne(Collection::class, 'op_eb_header_id', 'id');
    }


    public function cancellation()
    {
        return $this->hasOne(Cancellation::class, 'op_eb_header_id', 'id');
    }


    public function retailerProduct()
    {
        return $this->belongsTo(RetailerProduct::class, 'ad_retailer_product_id', 'id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'ad_certificate_id', 'id');
    }


    public function agency()
    {
        return $this->belongsTo(Agency::class, 'ad_agency_id', 'id');
    }


    public function cashier()
    {
        return $this->belongsTo(Cashier::class, 'ad_cashier_id', 'id');
    }


    public function getCompletedAttribute()
    {
        if (is_null($this->detail->beneficiary)) {
            return false;
        }

        return true;
    }
}
