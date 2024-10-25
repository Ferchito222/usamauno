<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'ad_emails';

    protected $fillable = [
        'email',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function retailerProducts()
    {
        return $this->belongsToMany(RetailerProduct::class, 'ad_retailer_product_emails', 'ad_email_id',
            'ad_retailer_product_id')->withPivot([ 'id', 'active' ]);
    }
}
