<?php

namespace Sibas\Entities\De;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\User;

class CreditLine extends Model
{
    protected $table = 'op_de_credit_lines';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'ad_user_id',
        'op_de_header_id',
        'number_line',
        'coverage',
        'policy_type',
        'free_cover',
        'confirmed',
        'deleted',
        'data_records',
    ];

    protected $casts = [
        'free_cover'   => 'boolean',
        'confirmed'    => 'boolean',
        'deleted'      => 'boolean',
        'data_records' => 'array',
    ];

    protected $hidden = [
        'id',
        'ad_user_id',
        'op_de_header_id',
        'data_records',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'idc',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }


    public function header()
    {
        return $this->belongsTo(Header::class, 'op_de_header_id', 'id');
    }


    public function clients()
    {
        return $this->hasMany(CreditLineClient::class, 'op_de_credit_line_id', 'id');
    }


    public function getIdcAttribute()
    {
        return encode($this->attributes['id']);
    }
}
