<?php

namespace Sibas\Entities\Rma;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\RetailerProductCategory;

class Detail extends Model
{
    protected $table = 'op_rma_details';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_rma_header_id',
        'insured_value',
        'property_use',
        'description',
        'serial_number',
        'city',
        'rate',
        'rate_month',
        'premium',
        'premium_month',
        'approved',
        'rejected',
        'guarantee_id',
    ];

    public function header()
    {
        return $this->belongsTo(Header::class, 'op_rma_header_id', 'id');
    }

    public function facultative()
    {
        return $this->hasOne(Facultative::class, 'op_rma_detail_id', 'id');
    }

    public function getCompletedAttribute()
    {
        return true;
    }
}
