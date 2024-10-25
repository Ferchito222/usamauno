<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Retailer\RetailerUserProduct;

class RetailerUser extends Model
{

    protected $table = 'ad_retailer_users';

    protected $fillable = [
        'id',
        'ad_retailer_id',
        'ad_user_id',
        'companies',
        'active',
    ];

    protected $casts = [
        'companies' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function retailer()
    {
        return $this->belongsTo(Retailer::class, 'ad_retailer_id', 'id');
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'ad_retailer_user_products', 'ad_retailer_user_id',
            'ad_product_id');
    }


    public function userProducts()
    {
        return $this->hasMany(RetailerUserProduct::class, 'ad_retailer_user_id', 'id');
    }
}
