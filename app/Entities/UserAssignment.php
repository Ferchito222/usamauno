<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    protected $table = 'ad_user_assignments';

    protected $fillable = [
        'id',
        'ad_user_id',
        'assignment',
    ];

    protected $appends = [
        'campaigns',
    ];


    public function getCampaignsAttribute()
    {
        $campaigns = explode('|', trim($this->assignment, '|'));

        foreach ($campaigns as &$campaign) {
            $campaign = explode(':', $campaign);
        }

        return $campaigns;
    }
}
