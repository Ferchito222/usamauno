<?php

namespace Sibas\Http\Controllers\Vd;

use Sibas\Entities\Vd\Beneficiary;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Vd\BeneficiaryFormRequest;
use Sibas\Repositories\Vd\BeneficiaryRepository;
use Sibas\Repositories\Vd\DetailRepository;
use Sibas\Repositories\De\DataRepository;
use Sibas\Repositories\Retailer\CityRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class BeneficiaryController extends Controller
{
    /**
     * @var BeneficiaryRepository
     */
    protected $repository;

    /**
     * @var DetailRepository
     */
    protected $detailRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @var DataRepository
     */
    protected $dataRepository;

    public function __construct(
        BeneficiaryRepository $repository,
        DetailRepository $detailRepository,
        RetailerProductRepository $retailerProductRepository,
        CityRepository $cityRepository
    ) {
        $this->repository                = $repository;
        $this->detailRepository          = $detailRepository;
        $this->cityRepository            = $cityRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->dataRepository            = new DataRepository();
    }

    public function create($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->detailRepository->getDetailById(decode($detail_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $detail          = $this->detailRepository->getModel();
                $beneficiary     = new Beneficiary();
                
                $data = [
                    'cities' => $this->cityRepository->getCitiesByType(),
                    'vg'     => false,
                    'relationships' => $this->dataRepository->getRelationship(),
                ];
                
                $payload = view('beneficiary.create', compact('rp_id', 'header_id', 'detail', 'beneficiary', 'data', 'retailerProduct'));

                return response()->json([
                    'payload' => $payload->render()
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

    public function store(BeneficiaryFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if ($request->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->detailRepository->getDetailById(decode($detail_id))) {
                $retailerProduct    = $this->retailerProductRepository->getModel();
                $request['detail']  = $this->detailRepository->getModel();
                
                if ($this->repository->storeBeneficiary($request)) {
                    return response()->json([
                        'location' => route($retailerProduct->companyProduct->product->code.'.edit', compact('rp_id', 'header_id'))
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

    public function edit($rp_id, $header_id, $detail_id, $beneficiary_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->detailRepository->getDetailById(decode($detail_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $detail          = $this->detailRepository->getModel();

                $beneficiary     = $detail->beneficiary()->where('id', decode($beneficiary_id))->first();

                $data = [
                    'cities' => $this->cityRepository->getCitiesByType(),
                    'vg'     => false,
                    'relationships' => $this->dataRepository->getRelationship(),
                ];

                $payload = view('beneficiary.edit', compact('rp_id', 'header_id', 'detail', 'beneficiary', 'data', 'beneficiary_id', 'retailerProduct'));

                return response()->json([
                    'payload'     => $payload->render(),
                    'beneficiary' => [ $beneficiary ]
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

    public function update(BeneficiaryFormRequest $request, $rp_id, $header_id, $detail_id, $beneficiary_id)
    {
        if ($request->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->detailRepository->getDetailById(decode($detail_id)) && $this->repository->getBeneficiaryById(decode($beneficiary_id))) {
                $retailerProduct    = $this->retailerProductRepository->getModel();
                $request['detail']  = $this->detailRepository->getModel();

                if ($this->repository->updateBeneficiary($request)) {
                    return response()->json([
                        'location' => route($retailerProduct->companyProduct->product->code.'.edit', compact('rp_id', 'header_id'))
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

    public function destroy($rp_id, $header_id, $beneficiary_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getBeneficiaryById(decode($beneficiary_id)) && $this->repository->removeBeneficiary()) {
                $retailerProduct    = $this->retailerProductRepository->getModel();
                return response()->json([
                    'location' => route($retailerProduct->companyProduct->product->code.'.edit', compact('rp_id', 'header_id'))
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }
}