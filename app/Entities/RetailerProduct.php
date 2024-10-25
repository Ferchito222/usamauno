<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Goal;
use Sibas\Entities\De\Coverage;
use Sibas\Entities\Retailer\Commission;

class RetailerProduct extends Model
{

    protected $table = 'ad_retailer_products';

    public $incrementing = false;

    protected $hidden = [
        'ad_retailer_id',
        'ad_company_product_id',
        'created_at',
        'updated_at',
    ];

    protected $with = [
        'companyProduct',
        'forms',
    ];

    protected $casts = [
        'warranty'    => 'boolean',
        'facultative' => 'boolean',
        'ws'          => 'boolean',
        'active'      => 'boolean',
    ];

    protected $appends = [
        'idc',
        'product_code',
    ];


    public function productQuestions()
    {
        return $this->hasMany(RetailerProductQuestion::class, 'ad_retailer_product_id', 'id');
    }


    public function rates()
    {
        return $this->hasMany(Rate::class, 'ad_retailer_product_id', 'id');
    }


    public function companyProduct()
    {
        return $this->belongsTo(CompanyProduct::class, 'ad_company_product_id', 'id');
    }


    public function subProducts()
    {
        return $this->hasMany(RetailerSubProduct::class, 'ad_retailer_product_id', 'id');
    }


    public function plans()
    {
        return $this->hasMany(Plan::class, 'ad_retailer_product_id', 'id');
    }


    public function parameters()
    {
        return $this->hasMany(ProductParameter::class, 'ad_retailer_product_id', 'id');
    }


    public function retailer()
    {
        return $this->belongsTo(Retailer::class, 'ad_retailer_id', 'id');
    }


    public function emails()
    {
        return $this->belongsToMany(Email::class, 'ad_retailer_product_emails', 'ad_retailer_product_id',
            'ad_email_id')->withPivot(['id', 'active']);
    }


    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'ad_retailer_product_activities', 'ad_retailer_product_id',
            'ad_activity_id');
    }


    public function forms()
    {
        return $this->hasMany(Form::class, 'ad_retailer_product_id', 'id');
    }


    public function content()
    {
        return $this->hasOne(Content::class, 'ad_retailer_product_id', 'id');
    }


    public function coverages()
    {
        return $this->belongsToMany(Coverage::class, 'ad_retailer_product_coverages', 'ad_retailer_product_id',
            'ad_coverage_id')->withPivot('detail');
    }


    public function modalities()
    {
        return $this->hasMany(Modality::class, 'ad_retailer_product_id', 'id');
    }


    public function categories()
    {
        return $this->hasMany(RetailerProductCategory::class, 'ad_retailer_product_id', 'id');
    }


    public function paymentMethods()
    {
        return $this->hasMany(RetailerProductPaymentMethod::class, 'ad_retailer_product_id', 'id');
    }


    public function creditProducts()
    {
        return $this->hasMany(CreditProduct::class, 'ad_retailer_product_id', 'id');
    }


    public function creditProduct()
    {
        return $this->belongsTo(CreditProduct::class, 'ad_credit_product_id', 'id');
    }


    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'ad_retailer_product_id', 'id');
    }


    public function states()
    {
        return $this->belongsToMany(State::class, 'ad_retailer_product_states', 'ad_retailer_product_id',
            'ad_state_id');
    }


    public function commissions()
    {
        return $this->hasMany(Commission::class, 'ad_retailer_product_id', 'id');
    }


    public function getIdcAttribute()
    {
        return encode($this->id);
    }

    public function getProductCodeAttribute()
    {
        if($this->companyProduct)
            return $this->companyProduct->product->code;
        else
            return false;
    }

    public function policies()
    {
        return $this->hasMany(Policy::class, 'ad_retailer_product_id', 'id');
    }
    
    public function goals()
    {
        return $this->hasOne(Goal::class, 'ad_retailer_product_id', 'id');
    }
}
