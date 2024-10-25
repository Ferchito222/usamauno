<?php

namespace Sibas\Repositories\De;

use Illuminate\Database\Eloquent\Collection;
use Sibas\Entities\De\Coverage;
use Sibas\Repositories\BaseRepository;

class CoverageRepository extends BaseRepository
{

    /** Returns list of Coverages
     *
     * @return Collection
     */
    public function getCoverage()
    {
        $selectOption = $this->getSelectOption();

        $coverages = Coverage::select('id', 'name', 'slug as data_coverage')->get();

        $coverages = $selectOption->merge($coverages->toArray());

        return $coverages;
    }

}