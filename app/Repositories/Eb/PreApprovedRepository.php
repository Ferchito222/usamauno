<?php

namespace Sibas\Repositories\Eb;

use Illuminate\Http\Request;
use Sibas\Entities\Eb\Header;
use Sibas\Http\Controllers\ReportTrait;

class PreApprovedRepository
{

    use ReportTrait;

    public function getHeaderList(Request $request)
    {
        $headers = Header::with('detail.client', 'user.city', 'user.agency')
            ->where('type', 'I')
            ->where('issued', false)
            ->where('canceled', false);

        $this->filtersByHeader($request, $headers, 'eb');

        return $headers->get();
    }
}