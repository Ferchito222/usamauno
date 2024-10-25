<?php

namespace Sibas\Repositories\Au;

use Illuminate\Http\Request;
use Sibas\Entities\Au\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class PreApprovedRepository extends BaseRepository
{

    use ReportTrait;


    public function getHeaderList(Request $request)
    {
        $headers = Header::with([
            'details',
            'details.vehicleType',
            'details.vehicleMake',
            'details.vehicleModel',
            'client',
            'user.city',
            'user.agency'
        ])->where('type', 'I')->where('issued', false)->where('canceled', false)->where('facultative', false);
        
        $this->filtersByHeader($request, $headers, 'au');
        
        return $headers->get();
    }
}