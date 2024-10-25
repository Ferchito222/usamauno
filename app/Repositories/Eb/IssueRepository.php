<?php

namespace Sibas\Repositories\Eb;

use Sibas\Entities\Eb\Header;
use Sibas\Http\Controllers\ReportTrait;

class IssueRepository
{

    use ReportTrait;


    public function getHeaderList($request)
    {
        $headers = Header::with('detail.client', 'user.city', 'user.agency')->where('type', 'Q');

        $this->filtersByHeader($request, $headers, 'eb');

        return $headers->get();
    }
}