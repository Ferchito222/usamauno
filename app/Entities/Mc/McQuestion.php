<?php

namespace Sibas\Entities\Mc;

use Illuminate\Database\Eloquent\Model;

class McQuestion extends Model
{
    protected $table = 'mc_questions';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
