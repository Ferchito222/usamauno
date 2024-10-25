<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Au\Header as HeaderAu;
use Sibas\Entities\Td\Header as HeaderTd;

class CoverageWarranty extends Model
{

    protected $table = 'op_de_coverage_warranties';

    protected $fillable = [
        'id',
        'op_de_header_id',
        'op_au_header_id',
        'op_td_header_id',
    ];


    public function au()
    {
        return $this->belongsTo(HeaderAu::class, 'op_au_header_id', 'id');
    }


    public function td()
    {
        return $this->belongsTo(HeaderTd::class, 'op_td_header_id', 'id');
    }

}