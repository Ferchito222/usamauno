<?php

namespace Sibas\Entities\Elp;

use Illuminate\Database\Eloquent\Model;

class CollectionRecord extends Model
{

    protected $table = 'op_elp_collection_records';

    protected $fillable = [
        'date',
        'total_record',
        'total_amount',
        'date_shipping',
        'confirmation',
    ];

    protected $casts = [
        'confirmation' => 'boolean',
    ];
}
