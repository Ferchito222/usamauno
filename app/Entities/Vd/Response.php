<?php

namespace Sibas\Entities\Vd;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $table = 'op_vd_responses';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'response',
        'observation',
    ];
    
    public function detail()
    {
        return $this->belongsTo(Detail::class, 'op_vd_detail_id', 'id');
    }
}
