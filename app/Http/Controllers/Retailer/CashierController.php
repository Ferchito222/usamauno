<?php

namespace Sibas\Http\Controllers\Retailer;

use Illuminate\Http\Request;

use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Eb\HeaderRepository as HeaderEbRepository;
use Sibas\Repositories\Retailer\CashierRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class CashierController extends Controller
{

    /**
     * @var CashierRepository
     */
    private $repository;
    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;
    /**
     * @var HeaderEbRepository
     */
    private $headerEbRepository;


    public function __construct(
        CashierRepository $repository,
        RetailerProductRepository $retailerProductRepository,
        HeaderEbRepository $headerEbRepository
    )
    {
        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->headerEbRepository        = $headerEbRepository;
    }


    use DataTrait;


    public function index()
    {
        $cashier          = $this->repository->getCashier();
        $retailerProducts = $this->repository->getRetailerProducts($cashier);

        return view('cashier.panel', [ 'cashier' => $cashier, 'retailerProducts' => $retailerProducts ]);
    }


    public function showOrder($code, $rp_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $cashier          = $this->repository->getCashier();
            $rp               = $this->retailerProductRepository->getModel();
            $retailerProducts = $this->repository->getRetailerProducts($cashier);
            $data             = $this->getData($rp_id);
            $header           = null;

            if (session('header')) {
                $header = session('header');
            } elseif (request()->has('h') && $this->headerEbRepository->getHeaderById(decode(request()->get('h')))) {
                $header = $this->headerEbRepository->getModel();
            }

            return view('cashier.panel-' . $code, [
                'code'             => $code,
                'rp_id'            => $rp_id,
                'cashier'          => $cashier,
                'rp'               => $rp,
                'retailerProducts' => $retailerProducts,
                'data'             => $data,
                'header'           => $header,
            ]);
        }

        return redirect()->route('cashiers::panel');
    }


    /**
     * @param Request $request
     * @param string  $code
     * @param string  $rp_id
     *
     * @return \Illuminate\Http\RedirectResponse|CashierController
     */
    public function searchOrder(Request $request, $code, $rp_id)
    {
        $this->validate($request, [
            'issue_number' => 'required|numeric',
            'dni'          => 'required|alpha_dash',
            'complement'   => 'alpha',
            'extension'    => 'required|exists:ad_cities,abbreviation',
        ]);

        $header = $this->headerEbRepository->getOrder($request);

        if (! is_null($header)) {
            return redirect()->route('cashiers::order.show', [
                'code'  => $code,
                'rp_id' => $rp_id,
                'h'     => encode($header->id)
            ])->withInput()->with([ 'header' => $header ]);
        }

        return redirect()->back()->withErrors([ 'error_order' => 'La orden de cobro no Existe' ])->withInput();
    }


    /**
     * @param string $code
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function orderEdit($code, $rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
                && $this->headerEbRepository->getHeaderById(decode($header_id))
            ) {
                $header = $this->headerEbRepository->getModel();

                $payload = view('cashier.order-edit', compact('code', 'rp_id', 'header'));

                return response()->json([
                    'payload' => $payload->render(),
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param Request $request
     * @param string  $code
     * @param string  $rp_id
     * @param string  $header_id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function orderUpdate(Request $request, $code, $rp_id, $header_id)
    {
        if (request()->ajax()) {
            $this->validate($request, [
                'voucher' => 'required|alpha_num',
            ]);

            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
                && $this->headerEbRepository->getHeaderById(decode($header_id))
            ) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $header          = $this->headerEbRepository->getModel();
                $cashier         = $request->session()->get('cashier');
                $voucher         = $request->get('voucher');
                $reason          = '';
                $errors          = [];

                if (! $this->headerEbRepository->checkVoucher($voucher)) {
                    $flag = false;

                    if ($retailerProduct->ws) {
                        // Verificar el voucher WS
                        // $reason = WS_detail

                        $errors['check_voucher'] = 'Voucher no válido.';
                    } else {
                        $flag = true;
                    }

                    if ($flag && $this->headerEbRepository->issuanceHeader($header, $cashier, $voucher, $reason)) {
                        return response()->json([
                            'location' => route('cashiers::order.show', [
                                'code'  => $code,
                                'rp_id' => $rp_id,
                                'h'     => $header_id
                            ])
                        ]);
                    } else {
                        $errors['check_voucher'] = 'El Voucher no pudo ser registrado.';
                    }
                } else {
                    $errors['check_voucher'] = 'Este Voucher ya fue utilizado previamente, por favor introduzca uno nuevo.';
                }

                return response()->json($errors, 422);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param string  $code
     * @param string  $rp_id
     * @param string  $format
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function report(Request $request, $code, $rp_id, $format = null)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $cashier         = $this->repository->getCashier();
            $rps             = $this->repository->getRetailerProducts($cashier);
            $data            = $this->getData($rp_id);
            $headers         = $this->repository->getDataReport($request, $cashier, $code, $format);

            if ($format === 'xls') {
                $view = 'cashier.rows-' . $code;

                if (view()->exists($view)) {
                    $title = 'Ecoaguinaldo';

                    $this->repository->exportExcel($headers, $view, $title);
                }
            }

            $request->flash();

            return view('cashier.report', [
                'code'             => $code,
                'rp_id'            => $rp_id,
                'cashier'          => $cashier,
                'headers'          => $headers,
                'retailerProducts' => $rps,
                'data'             => $data,
            ]);
        }

        return redirect()->back();
    }
}
