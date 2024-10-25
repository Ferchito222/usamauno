<?php

namespace Sibas\Repositories\Elp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sibas\Entities\Elp\Header;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class CancellationRepository extends BaseRepository
{
    use ReportTrait;
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getHeaderList(Request $request)
    {
        $headers = Header::with('detail.client', 'user.city', 'user.agency', 'state')
            ->where('type', 'I')
            ->where('canceled', false);

        $this->filtersByHeader($request, $headers, 'elp');

        return $headers->get();
    }

    /**
     * @param Request $request
     * @param Model $header
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

        $this->model->canceled    = true;
        $this->model->ad_state_id = 10;

        return $this->saveModel();
    }
}