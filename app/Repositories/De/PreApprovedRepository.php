<?php

namespace Sibas\Repositories\De;

use Illuminate\Http\Request;
use Sibas\Entities\De\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class PreApprovedRepository extends BaseRepository
{
    use ReportTrait;

    public function getHeaderList(Request $request)
    {
        $headers = Header::with('details.client', 'user.city', 'user.agency')
            ->where('type', 'I')
            ->where('issued', false)
            ->where('canceled', false)
            ->where('facultative', false);

        $this->filtersByHeader($request, $headers);

        return $headers->get();
    }
}