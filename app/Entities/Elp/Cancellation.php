<?php

namespace Sibas\Entities\Elp;

use Illuminate\Database\Eloquent\Model;

class Cancellation extends Model
{

    protected $table = 'op_elp_cancellations';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_elp_header_id',
        'ad_user_id',
        'reason',
    ];


    public function setReasonAttribute($value)
    {
        $this->attributes['reason'] = mb_strtoupper($value);
    }
}
