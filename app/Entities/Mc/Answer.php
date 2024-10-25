<?php

namespace Sibas\Entities\Mc;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\CareCenter;
use Sibas\Entities\User;

class Answer extends Model
{

    protected $table = 'op_mc_answers';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'medical_certificate_number',
        'ad_user_id',
        'mc_certificate_id',
        'ad_care_center_id',
        'center_attention',
        'contact_person',
        'date_attention',
        'response',
    ];

    protected $appends = [
        'creation_date',
    ];

    protected $hidden = [
        'ad_user_id',
        'mc_certificate_id',
        'ad_care_center_id',
        'created_at',
        'updated_at',
    ];


    public function getCreationDateAttribute()
    {
        $time = Carbon::createFromTimestamp(strtotime($this->created_at));

        return $time->day . ' de ' . monthTranslated($time->month) . ' de ' . $time->year;
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function careCenter()
    {
        return $this->belongsTo(CareCenter::class, 'ad_care_center_id', 'id');
    }

}