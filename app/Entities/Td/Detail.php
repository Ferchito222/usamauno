<?php

namespace Sibas\Entities\Td;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\RetailerProductCategory;

class Detail extends Model
{

    protected $table = 'op_td_details';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_td_header_id',
        'policy_number_td',
        'policy_number_mr',
        'nro_folio_real',
        'matter_insured',
        'matter_description',
        'number',
        'use',
        'state',
        'make',
        'model',
        'chassis_number',
        'city',
        'zone',
        'locality',
        'address',
        'construction_value',
        'rate_const',
        'premium_const',
        'remodeling_value',
        'rate_remod',
        'premium_remod',
        'insured_value',
        'rate',
        'rate_month',
        'premium',
        'premium_month',
        'approved',
        'rejected',
        'construction_term',
    ];

    protected $appends = [
        'matter_insured_text',
        'use_text',
        'completed',
    ];


    public function category()
    {
        return $this->belongsTo(RetailerProductCategory::class, 'ad_retailer_product_category_id', 'id');
    }


    public function header()
    {
        return $this->belongsTo(Header::class, 'op_td_header_id', 'id');
    }


    public function facultative()
    {
        return $this->hasOne(Facultative::class, 'op_td_detail_id', 'id');
    }


    public function getMatterInsuredTextAttribute()
    {
        return config('base.property_types.' . $this->matter_insured);
    }


    public function getUseTextAttribute()
    {
        return config('base.property_uses.' . $this->use);
    }


    public function getCompletedAttribute()
    {
        return ( empty( $this->address ) || empty( $this->locality ) || empty( $this->zone ) ) ? false : true;

        return $completed;
    }


    public function setMatterDescriptionAttribute($value)
    {
        $this->attributes['matter_description'] = strtoupper($value);
    }


    public function setZoneAttribute($value)
    {
        $this->attributes['zone'] = strtoupper($value);
    }


    public function setLocalityAttribute($value)
    {
        $this->attributes['locality'] = strtoupper($value);
    }


    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtoupper($value);
    }

}
