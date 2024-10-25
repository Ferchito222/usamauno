<?php

namespace Sibas\Repositories\Au;

use Sibas\Entities\Au\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class IssueRepository extends BaseRepository
{

    use ReportTrait;


    public function getHeaderList($request)
    {
        $headers = Header::with([
            'details',
            'details.vehicleType',
            'details.vehicleMake',
            'details.vehicleModel',
            'client',
            'user.city',
            'user.agency'
        ])->where('type', 'Q');

        $this->filtersByHeader($request, $headers, 'au');

        return $headers->get();
    }
}