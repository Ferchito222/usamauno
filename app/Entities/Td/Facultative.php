<?php

namespace Sibas\Entities\Td;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\User;

class Facultative extends Model
{

    protected $table = 'op_td_facultatives';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'ad_user_id',
        'reason',
        'state',
        'approved',
        'surcharge',
        'percentage',
        'current_rate',
        'final_rate',
        'observation',
        'read',
    ];

    protected $casts = [
        'approved'  => 'boolean',
        'surcharge' => 'boolean',
        'read'      => 'boolean',
    ];

    protected $appends = [
        'company_state',
        'process_days',
        'date_admission',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function detail()
    {
        return $this->belongsTo(Detail::class, 'op_td_detail_id', 'id');
    }


    public function observations()
    {
        return $this->hasMany(Observation::class, 'op_td_facultative_id', 'id');
    }


    public function getCompanyStateAttribute()
    {
        $state = 'P';

        switch ($this->state) {
            case 'PR':
                if ($this->approved) {
                    $state = 'A';
                } else {
                    $state = 'R';
                }
                break;
            case 'PE':
                if ($this->observations->count() > 0) {
                    $state = 'O';

                    if ($this->observations->last()->response) {
                        $state = 'C';
                    }
                }

                break;
        }

        return $state;
    }


    public function getProcessDaysAttribute()
    {
        $days = 0;

        if ($this->state === 'PR') {
            $dt   = new Carbon($this->updated_at);
            $days = $dt->diffInDays($this->created_at);
        } else {
            $now  = new Carbon();
            $days = $now->diffInDays($this->created_at);
        }

        return $days;
    }


    public function getDateAdmissionAttribute()
    {
        $now = new Carbon();
        $dt  = new Carbon($this->created_at);
        $dt->setLocale('es');

        return $dt->diffForHumans($now);
    }

}
