<?php

namespace Sibas\Entities\Elp;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Agency;
use Sibas\Entities\Certificate;
use Sibas\Entities\City;
use Sibas\Entities\De\Coverage;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\State;
use Sibas\Entities\User;

class Header extends Model
{

    protected $table = 'op_elp_headers';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'ad_user_id',
        'ad_retailer_product_id',
        'type',
        'issue_number',
        'quote_number',
        'prefix',
        'policy_number',
        'ad_coverage_id',
        'canceled',
        'issued',
        'date_issue',
        'approved',
        'rejected',
        'ad_state_id',
        'renewal',
        'renewal_detail',
        'ad_city_id',
        'ad_agency_id',
        'copy',
        'read',
        'ad_certificate_id',
    ];

    protected $appends = [
        'completed',
        'premium_ep',
        'premium_el',
        'product',
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
        return $this->hasOne(Detail::class, 'op_elp_header_id', 'id');
    }


    public function collections()
    {
        return $this->hasMany(Collection::class, 'op_elp_header_id', 'id');
    }


    public function cancellation()
    {
        return $this->hasOne(Cancellation::class, 'op_elp_header_id', 'id');
    }


    public function retailerProduct()
    {
        return $this->belongsTo(RetailerProduct::class, 'ad_retailer_product_id', 'id');
    }


    public function state()
    {
        return $this->belongsTo(State::class, 'ad_state_id', 'id');
    }


    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'ad_certificate_id', 'id');
    }


    public function city()
    {
        return $this->belongsTo(City::class, 'ad_city_id', 'id');
    }


    public function agency()
    {
        return $this->belongsTo(Agency::class, 'ad_agency_id', 'id');
    }


    public function getCompletedAttribute()
    {
        if ( ! $this->detail->completed_client || is_null($this->detail->beneficiary)) {
            return false;
        }

        return true;
    }


    public function getPremiumEpAttribute()
    {
        return 176;
    }


    public function getPremiumElAttribute()
    {
        return 100;
    }


    public function getProductAttribute()
    {
        return $this->detail->client_eco ? 'Ecoprotección' : 'Ecovida';
    }
}
