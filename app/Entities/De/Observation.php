<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Mc\Answer;
use Sibas\Entities\State;
use Sibas\Entities\User;

class Observation extends Model
{
    protected $table = 'op_de_observations';

    public $incrementing = false;

    protected $casts = [
        'response' => 'boolean',
    ];

    protected $fillable = [
        'id',
        'op_de_facultative_id',
        'ad_user_id',
        'ad_state_id',
        'observation',
        'response',
        'observation_response',
        'date_response',
        'medical_certificate_id',
    ];

    protected $hidden = [
        'op_de_facultative_id',
        'ad_user_id',
        'ad_state_id',
        'op_mc_answer_id',
        'created_at',
        'updated_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'ad_state_id', 'id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'op_mc_answer_id', 'id');
    }
}
