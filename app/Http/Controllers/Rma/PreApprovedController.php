<?php

namespace Sibas\Http\Controllers\Rma;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\Rma\PreApprovedRepository;

class PreApprovedController extends Controller
{

    use ReportTrait;

    /**
     * @var PreApprovedRepository
     */
    protected $repository;


    public function __construct(PreApprovedRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param Guard   $auth
     * @param Request $request
     * @param string  $rp_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Guard $auth, Request $request, $rp_id)
    {
        $data    = $this->data($auth->user());
        $headers = [ ];
        $code    = '';
        
        if ($request->has('_token')) {
            $request->flash();
        }

        $headers = $this->repository->getHeaderList($request);

        return view('rma.pre-approved.list', compact('rp_id', 'headers', 'data', 'code'));
    }
}
