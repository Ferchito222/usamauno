<?php

namespace Sibas\Repositories\Retailer;

use Sibas\Entities\Plan;
use Sibas\Repositories\BaseRepository;

class PlanRepository extends BaseRepository
{
    public function getPlanByProduct($rp_id = null)
    {
        $selectOption = $this->getSelectOption();

        $query = Plan::select('*');

        if (! is_null($rp_id)) {
            $query->where('ad_retailer_product_id', $rp_id);
        }

        $this->model = $query->orderBy('id', 'asc')->get();

        $plans = $selectOption->merge($this->model->toArray());

        return $plans;
    }

    public function getPlansByProduct($rp_id)
    {
        return Plan::where('ad_retailer_product_id', $rp_id)->get();
    }
}