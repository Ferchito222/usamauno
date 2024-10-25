<?php

namespace Sibas\Entities\Vi;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Certificate;
use Sibas\Entities\Plan;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\User;

class Header extends Model
{

    protected $table = 'op_vi_headers';

    public $incrementing = false;

    protected $casts = [
        'issued'   => 'boolean',
        'canceled' => 'boolean',
    ];

    protected $fillable = [
        'ad_certificate_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function plan()
    {
        return $this->belongsTo(Plan::class, 'ad_plan_id', 'id');
    }


    public function details()
    {
        return $this->hasMany(Detail::class, 'op_vi_header_id', 'id');
    }


    public function retailerProduct()
    {
        return $this->belongsTo(RetailerProduct::class, 'ad_retailer_product_id', 'id');
    }


    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'ad_certificate_id', 'id');
    }
}
