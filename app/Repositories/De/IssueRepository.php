<?php

namespace Sibas\Repositories\De;

use Sibas\Entities\De\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class IssueRepository extends BaseRepository
{
    use ReportTrait;

    public function getHeaderList($request)
    {
        $headers = Header::with([
            'details.client',
            'user.city',
            'user.agency',
            'guest.city',
        ])
            ->where('type', 'Q')
            ->where(function ($q) {
                $q->where(function ($q) {
                    $q->where('ad_coverage_id', 1)
                        ->has('details', '=', 1);
                })
                ->orWhere(function ($q) {
                    $q->where('ad_coverage_id', 2)
                        ->has('details', '>', 1);
                });
            });

        $this->filtersByHeader($request, $headers);

        return $headers->get();
    }
}