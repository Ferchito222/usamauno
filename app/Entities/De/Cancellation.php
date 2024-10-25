<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\User;

class Cancellation extends Model
{
    protected $table = 'op_de_cancellations';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_de_header_id',
        'ad_user_id',
        'reason',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function setReasonAttribute($value)
    {
        $this->attributes['reason'] = mb_strtoupper($value);
    }

}
