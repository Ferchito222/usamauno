<?php

namespace Sibas\Repositories\Eb;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sibas\Entities\Eb\Header;
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
        $headers = Header::with('detail.client', 'user.city', 'user.agency')
            ->where('type', 'I')
            ->where('canceled', false);

        $this->filtersByHeader($request, $headers, 'eb');

        return $headers->get();
    }

    /**
     * @param Request      $request
     * @param Model|Header $header
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
            'reason'     => $this->data['reason'],
        ]);

        $this->model->canceled = true;

        return $this->saveModel();
    }
}