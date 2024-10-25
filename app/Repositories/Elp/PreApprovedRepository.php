<?php

namespace Sibas\Repositories\Elp;

use Illuminate\Http\Request;
use Sibas\Entities\Elp\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class PreApprovedRepository extends BaseRepository
{
    use ReportTrait;

    public function getHeaderList(Request $request)
    {
        $headers = Header::with('detail.client', 'user.city', 'user.agency')
            ->where('type', 'I')
            ->where('issued', false)
            ->where('canceled', false);

        $this->filtersByHeader($request, $headers, 'elp');

        return $headers->get();
    }
}