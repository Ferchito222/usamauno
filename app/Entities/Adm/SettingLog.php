<?php

namespace Sibas\Entities\Adm;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\User;

class SettingLog extends Model
{
    protected $table = 'ad_setting_logs';

    protected $fillable = [
        'id',
        'ad_setting_id',
        'value',
        'action_time',
        'change_message',
        'ad_user_id',
    ];

    protected $dates = [
        'action_time',
    ];


    public function setting()
    {
        return $this->belongsTo(Setting::class, 'ad_setting_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }
}
