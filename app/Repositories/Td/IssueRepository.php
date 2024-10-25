<?php

namespace Sibas\Repositories\Td;

use Sibas\Entities\Td\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class IssueRepository extends BaseRepository
{

    use ReportTrait;


    public function getHeaderList($request)
    {
        $headers = Header::with([
            'details',
            'details.category',
            'client',
            'user.city',
            'user.agency'
        ])->where('type', 'Q');

        $this->filtersByHeader($request, $headers, 'au');

        return $headers->get();
    }
}