<?php

namespace Sibas\Entities\Td;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\RetailerProductCategory;

class Increment extends Model
{

    protected $table = 'ad_td_increments';


    public function category()
    {
        return $this->belongsTo(RetailerProductCategory::class, 'ad_retailer_product_category_id', 'id');
    }

}
