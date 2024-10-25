<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\RetailerProductCoverage;

class Coverage extends Model
{
    protected $table = 'ad_coverages';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function retailerCoverages()
    {
        return $this->hasMany(RetailerProductCoverage::class, 'ad_coverage_id', 'id');
    }
}
