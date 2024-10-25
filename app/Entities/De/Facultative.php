<?php

namespace Sibas\Entities\De;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\User;

class Facultative extends Model
{

    protected $table = 'op_de_facultatives';

    public $incrementing = false;

    protected $fillable = [ 
        'id', 
        'reason', 
        'state', 
        'preference_rate', 
        'accidental_death',
        'percentage_bank',
        'percentage_cia',
        'monetary_pricing',
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

    protected $hidden = [
        'op_de_detail_id',
        'ad_user_id',
        'created_at',
        'updated_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function detail()
    {
        return $this->belongsTo(Detail::class, 'op_de_detail_id', 'id');
    }


    public function observations()
    {
        return $this->hasMany(Observation::class, 'op_de_facultative_id', 'id');
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
