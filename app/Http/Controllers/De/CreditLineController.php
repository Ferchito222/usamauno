<?php

namespace Sibas\Http\Controllers\De;

use Illuminate\Http\Request;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\De\CreditLineCreateFormRequest;
use Sibas\Repositories\De\CreditLineRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\WsRepository;

class CreditLineController extends Controller
{

    /**
     * @var CreditLineRepository
     */
    protected $repository;
    /**
     * @var WsRepository
     */
    protected $ws;
    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;


    public function __construct(
        CreditLineRepository $repository,
        WsRepository $ws,
        RetailerProductRepository $retailerProductRepository
    ) {
        $this->repository                = $repository;
        $this->ws                        = $ws;
        $this->retailerProductRepository = $retailerProductRepository;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $policy_types = $this->repository->getPolicyTypes();

        return view('de.credit-line.create', compact('policy_types'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreditLineCreateFormRequest $request

     * @return \Illuminate\Http\Response
     */
    public function store(CreditLineCreateFormRequest $request)
    {
        \Debugbar::disable();

        if ($request->ajax()) {
            $success = false;
            $edit    = $request->get('edit');

            if ($edit) {
                if ($this->repository->updateCreditLine($request)) {
                    $success = true;
                }
            } else {
                $number_line = $request->get('numberLine');

                if ($this->repository->checkNumberLine($number_line)) {
                    if ($this->repository->storeCreditLine($request)) {
                        $success = true;
                    }
                } else {
                    return response()->json([
                        'warning' => true,
                        'message' => 'El Número de Línea Madre ' . $number_line . ' ya se encuentra registrado.',
                    ], 402);
                }
            }

            if ($success) {
                $creditLine = $this->repository->getApiModel();

                return response()->json([
                    'creditLine' => $creditLine,
                    'success'    => true,
                    'message'    => 'Los datos se guardaron correctamente.',
                ]);
            }

            return response()->json([
                'warning' => true,
                'message' => 'Los datos no se ' . (!$edit ? 'registraron' : 'actualizaron') . '.',
            ], 402);
        }

        return redirect()->back();
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getNumberClient()
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $dni        = request()->get('dni');
            $complement = request()->get('complement');
            $numbers    = [];

            if ($this->retailerProductRepository->getRetailerProductByCode('de')) {
                $retailerProduct = $this->retailerProductRepository->getModel();

                if ($retailerProduct->ws && $this->ws->getClient($dni, $complement)) {
                    $numbers = $this->ws->getNumbers();
                } elseif (! $retailerProduct->ws) {
                    for ($i = 1; $i <= 3; $i++) {
                        $numbers[] = mt_rand();
                    }
                }
            }

            return response()->json([
                'numbers' => $numbers,
            ]);
        }

        return redirect()->back();
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        \Debugbar::disable();

        if ($request->ajax()) {
            if ($this->repository->confirmCreditLine(decode($request->get('id')))) {
                return response()->json([
                    'confirm' => true,
                    'success' => true,
                    'message' => 'Línea de Crédito confirmada.',
                ]);
            }

            return response()->json([
                'warning' => true,
                'message' => 'Línea de Crédito no confirmada.',
            ], 402);
        }

        return redirect()->back();
    }


    /**
     * @string $id
     */
    public function getMoves($id)
    {
        $data = collect();

        if ($this->repository->getCreditLineById(decode($id))) {
            $credit_line = $this->repository->getModel();

            if (count($credit_line->data_records) > 0) {
                $data = $data->merge($credit_line->data_records);
            }
        }

        return view('de.credit-line.moves', compact('data'));
    }
}
