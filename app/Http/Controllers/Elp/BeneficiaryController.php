<?php

namespace Sibas\Http\Controllers\Elp;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Elp\Beneficiary;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Elp\BeneficiaryElpFormRequest;
use Sibas\Repositories\Elp\BeneficiaryRepository;
use Sibas\Repositories\Elp\HeaderRepository;
use Sibas\Repositories\Retailer\CityRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class BeneficiaryController extends Controller
{

    /**
     * @var BeneficiaryRepository
     */
    protected $repository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var HeaderRepository
     */
    private $headerRepository;


    public function __construct(
        BeneficiaryRepository $repository,
        HeaderRepository $headerRepository,
        RetailerProductRepository $retailerProductRepository
    ) {
        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->headerRepository          = $headerRepository;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Http\Response
     */
    public function create($rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $header          = $this->headerRepository->getModel();
                $beneficiary     = new Beneficiary();

                $payload = view('elp.beneficiary.create', compact('rp_id', 'header_id', 'header', 'beneficiary'));

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
     * @param BeneficiaryElpFormRequest $request
     * @param string                    $rp_id
     * @param string                    $header_id
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BeneficiaryElpFormRequest $request, $rp_id, $header_id)
    {
        if ($request->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $header = $this->headerRepository->getModel();
                $detail = $header->detail;

                if ($this->repository->storeBeneficiary($request, $detail)) {
                    return response()->json([
                        'location' => route('elp.edit.issuance', compact('rp_id', 'header_id'))
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
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $header          = $this->headerRepository->getModel();
                $beneficiary     = $header->detail->beneficiary;

                $payload = view('elp.beneficiary.edit', compact('rp_id', 'header_id', 'header', 'beneficiary'));

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
     * @param BeneficiaryElpFormRequest $request
     * @param string                    $rp_id
     * @param string                    $header_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(BeneficiaryElpFormRequest $request, $rp_id, $header_id)
    {
        if ($request->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $header = $this->headerRepository->getModel();
                $detail = $header->detail;

                if ($this->repository->updateBeneficiary($request, $detail)) {
                    return response()->json([
                        'location' => route('elp.edit.issuance', compact('rp_id', 'header_id'))
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

}
