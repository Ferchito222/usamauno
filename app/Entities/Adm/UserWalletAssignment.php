<?php

namespace Sibas\Entities\Adm;

use Illuminate\Database\Eloquent\Model;

class UserWalletAssignment extends Model
{
    protected $table = 'ad_user_wallet_assignments';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'ad_user_from_id',
        'ad_user_to_id',
        'product_code',
        'certificates',
        'disabled',
    ];

    protected $casts = [
        'certificates' => 'array',
    ];
}
