<?php

namespace Sibas\Http\Controllers\Vd;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Sibas\Entities\ProductParameter;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\Vd\IssueRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class IssueController extends Controller
{

    use ReportTrait;

    /**
     * @var IssueRepository
     */
    protected $repository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;


    public function __construct(
        IssueRepository $repository,
        RetailerProductRepository $retailerProductRepository
    ) {
        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
    }

    public function lists(Guard $auth, Request $request, $rp_id, $guest = null)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();    
            $data      = $this->data($auth->user());
            $headers   = [ ]; 

            if ($request->has('_token')) {
                $request->flash();
            }

            $parameter = $retailerProduct->parameters()->where('slug', 'GE')->first();

            if ($parameter instanceof ProductParameter) {
                $headers = $this->repository->getHeaderList($request);
            }

            return view($retailerProduct->companyProduct->product->code.'.quote.list', compact('rp_id', 'headers', 'data', 'parameter', 'retailerProduct'));
        }
        return redirect()->back();
    }
}