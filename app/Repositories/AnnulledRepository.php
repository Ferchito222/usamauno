<?php

namespace Sibas\Repositories;

use Sibas\Entities\Vm\Annulled;
use Sibas\Repositories\BaseRepository;

class AnnulledRepository extends BaseRepository
{   
    public function getAnnulledByActive()
    {
        $selectOption = $this->getSelectOption();

        $rp = Annulled::select(['id as id', 'description as name'])
            ->where('active', true)
            ->get();

        $model = $selectOption->merge($rp->toArray());

        return $model;
    }
}