<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $table = 'op_de_responses';

    protected $fillable = ['id', 'response', 'observation', ];

    public $incrementing = false;

    public function detail()
    {
        return $this->belongsTo(Detail::class, 'op_de_detail_id', 'id');
    }
}
