<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class Annulled extends Model
{
    protected $table = 'ad_annulleds';

    protected $fillable = [
        'id',
        'description',
        'code',
        'active'
    ];
}
