<?php

namespace Sibas\Entities\Tj;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\User;
use Sibas\Entities\Annulled;

class Cancellation extends Model
{
    protected $table = 'op_tj_cancellations';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_tj_header_id',
        'ad_annulleds_id',
        'ad_user_id',
        'reason',
        'code',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }

    public function Annulled()
    {
        return $this->belongsTo(Annulled::class, 'ad_annulleds_id', 'id');
    }
    
    public function setReasonAttribute($value)
    {
        $this->attributes['reason'] = mb_strtoupper($value);
    }

}
