<?php

namespace Sibas\Http\Controllers\Tj;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\Tj\PreApprovedRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class PreApprovedController extends Controller
{

    use ReportTrait;

    /**
     * @var PreApprovedRepository
     */
    protected $repository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;


    public function __construct(
            PreApprovedRepository $repository,
            RetailerProductRepository $retailerProductRepository
            )
    {
        $this->repository = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
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
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();    
            $data    = $this->data($auth->user());
            $headers = [ ];
            $code    = '';

            if ($request->has('_token')) {
                $request->flash();
            }

            $headers = $this->repository->getHeaderList($request);
            
            return view($retailerProduct->companyProduct->product->code.'.pre-approved.list', compact('rp_id', 'headers', 'data', 'code', 'retailerProduct'));
        }
        return redirect()->back();
    }
}
