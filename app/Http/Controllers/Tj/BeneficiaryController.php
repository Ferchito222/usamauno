<?php

namespace Sibas\Http\Controllers\Tj;

use Sibas\Entities\Tj\Beneficiary;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Tj\BeneficiaryDeFormRequest;
use Sibas\Repositories\Tj\BeneficiaryRepository;
use Sibas\Repositories\Tj\DetailRepository;
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


    /**
     * Show the form for creating a new resource.
     *
     * @param $rp_id
     * @param $header_id
     * @param $detail_id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
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
                
                $payload = view('beneficiary.create-general', compact('rp_id', 'header_id', 'detail', 'beneficiary', 'data', 'retailerProduct'));

                return response()->json([
                    'payload' => $payload->render()
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  BeneficiaryDeFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BeneficiaryDeFormRequest $request, $rp_id, $header_id, $detail_id)
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


    /**
     * Show the form for editing the specified resource.
     *
     * @param $rp_id
     * @param $header_id
     * @param $detail_id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
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

                $payload = view('beneficiary.edit-general', compact('rp_id', 'header_id', 'detail', 'beneficiary', 'data', 'beneficiary_id', 'retailerProduct'));

                return response()->json([
                    'payload'     => $payload->render(),
                    'beneficiary' => [ $beneficiary ]
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  BeneficiaryDeFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(BeneficiaryDeFormRequest $request, $rp_id, $header_id, $detail_id, $beneficiary_id)
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
