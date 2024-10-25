<?php

namespace Sibas\Repositories\Client;

use Sibas\Entities\Activity;
use Sibas\Entities\RetailerProduct;
use Sibas\Repositories\BaseRepository;

class ActivityRepository extends BaseRepository
{
    public function getActivities()
    {
        $selectOption = $this->getSelectOption();

        $activities = Activity::select('id', 'category', 'occupation')
            ->orderBy('id', 'asc')
            ->get();

        $activities = $selectOption->merge($activities->toArray());

        return $activities;
    }

    public function getActivitiesByProduct($rp_id)
    {
        $selectOption = $this->getSelectOption();

        $rp = RetailerProduct::with([
            'activities' => function ($query) {
                $query->addSelect(['ad_activities.id', 'occupation', 'code']);
            }
        ])
            ->where('id', $rp_id)->first();

        $activities = $selectOption->merge($rp->activities->toArray());

        return $activities;
    }
}