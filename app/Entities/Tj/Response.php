<?php

namespace Sibas\Entities\Tj;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $table = 'op_tj_responses';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'response',
        'observation',
    ];
    
    public function detail()
    {
        return $this->belongsTo(Detail::class, 'op_tj_detail_id', 'id');
    }

}
