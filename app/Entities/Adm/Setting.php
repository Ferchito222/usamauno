<?php

namespace Sibas\Entities\Adm;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'ad_settings';

    protected $fillable = [
        'id',
        'name',
        'codename',
        'model',
    ];


    public function logs()
    {
        return $this->hasMany(SettingLog::class, 'ad_setting_id', 'id');
    }
}
