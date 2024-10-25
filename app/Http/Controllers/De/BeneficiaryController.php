<?php

namespace Sibas\Http\Controllers\De;

use Sibas\Entities\De\Beneficiary;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\De\BeneficiaryDeFormRequest;
use Sibas\Repositories\De\BeneficiaryRepository;
use Sibas\Repositories\De\DetailRepository;
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
                    'vg'     => $this->repository->getStatusVg($retailerProduct, $detail->header),
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
            if ($this->detailRepository->getDetailById(decode($detail_id))) {
                $request['detail'] = $this->detailRepository->getModel();

                if ($this->repository->storeBeneficiary($request)) {
                    return response()->json([
                        'location' => route('de.edit', compact('rp_id', 'header_id'))
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
    public function edit($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->detailRepository->getDetailById(decode($detail_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $detail          = $this->detailRepository->getModel();
                $beneficiary     = $detail->beneficiary()->where('coverage', request()->get('type'))->first();

                $data = [
                    'cities' => $this->cityRepository->getCitiesByType(),
                    'vg'     => $this->repository->getStatusVg($retailerProduct, $detail->header),
                ];

                $payload = view('beneficiary.edit', compact('rp_id', 'header_id', 'detail', 'beneficiary', 'data', 'retailerProduct'));

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
    public function update(BeneficiaryDeFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if ($request->ajax()) {
            if ($this->detailRepository->getDetailById(decode($detail_id))) {
                $request['detail'] = $this->detailRepository->getModel();

                if ($this->repository->updateBeneficiary($request)) {
                    return response()->json([
                        'location' => route('de.edit', compact('rp_id', 'header_id'))
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

}
