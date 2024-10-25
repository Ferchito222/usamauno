<?php

namespace Sibas\Entities\Mc;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $table = 'mc_questionnaires';

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];
}
