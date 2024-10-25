<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;

class DetailCoverage extends Model
{

    protected $table = 'op_de_detail_coverages';

    public $incrementing = false;

    protected $casts = [
        'granted' => 'boolean',
    ];

    protected $fillable = [
        'id',
        'op_de_detail_id',
        'coverage',
        'comment',
        'granted',
    ];


    public function setCommentAttribute($value)
    {
        $this->attributes['comment'] = mb_strtoupper($value);
    }
}
