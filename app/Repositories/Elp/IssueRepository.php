<?php

namespace Sibas\Repositories\Elp;

use Sibas\Entities\Elp\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class IssueRepository extends BaseRepository
{
    use ReportTrait;

    public function getHeaderList($request)
    {
        $headers = Header::with('detail.client', 'user.city', 'user.agency')
            ->where('type', 'Q');

        $this->filtersByHeader($request, $headers, 'elp');

        return $headers->get();
    }
}