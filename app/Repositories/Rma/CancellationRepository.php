<?php

namespace Sibas\Repositories\Rma;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sibas\Entities\Rma\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class CancellationRepository extends BaseRepository
{

    use ReportTrait;


    /**
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getHeaderList(Request $request)
    {
        $headers = Header::with([
            'details',
            'client',
            'user.city',
            'user.agency'
        ])->where('type', 'I')
            ->where('canceled', false)
            ->orderBy('issue_number', 'desc');

        $this->filtersByHeader($request, $headers, 'rma');

        return $headers->get();
    }


    /**
     * @param Request $request
     * @param Model   $header
     *
     * @return bool
     */
    public function storeCancellation(Request $request, $header)
    {
        $user        = $request->user();
        $this->data  = $request->all();
        $this->model = $header;

        $this->model->cancellation()->create([
            'id'         => date('U'),
            'ad_user_id' => $user->id,
            'reason'     => $this->data['reason']
        ]);

        $this->model->canceled = true;

        return $this->saveModel();
    }
}