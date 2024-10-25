<?php

namespace Sibas\Entities\Td;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\User;

class Cancellation extends Model
{

    protected $table = 'op_td_cancellations';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_au_header_id',
        'ad_user_id',
        'reason'
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
