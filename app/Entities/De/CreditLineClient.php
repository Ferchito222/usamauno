<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;

class CreditLineClient extends Model
{
    protected $table = 'op_de_credit_line_clients';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_de_credit_line_id',
        'dni',
        'extension',
        'complement',
        'number',
        'numbers',
        'percentage_credit',
        'rate',
        'percentage',
        'terms',
        'insured',
        'headline',
    ];

    protected $casts = [
        'numbers' => 'array',
        'insured' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'op_de_credit_line_id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'idc',
        'disabled',
    ];


    public function setComplementAttribute($value)
    {
        $this->attributes['complement'] = mb_strtoupper($value);
    }


    public function setTermsAttribute($value)
    {
        $this->attributes['terms'] = mb_strtoupper($value);
    }


    public function getIdcAttribute()
    {
        return encode($this->attributes['id']);
    }


    public function getDisabledAttribute()
    {
        return ! $this->attributes['insured'];
    }
}
