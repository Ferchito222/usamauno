<?php

namespace Sibas\Entities\Au;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\State;
use Sibas\Entities\User;

class Observation extends Model
{

    protected $table = 'op_au_observations';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'ad_user_id',
        'ad_state_id',
        'observation',
        'response',
        'observation_response',
        'date_response',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function state()
    {
        return $this->belongsTo(State::class, 'ad_state_id', 'id');
    }

}
