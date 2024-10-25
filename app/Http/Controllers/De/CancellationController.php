<?php

namespace Sibas\Http\Controllers\De;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Sibas\Http\Controllers\MailController;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\De\CancellationRepository;
use Sibas\Repositories\De\HeaderRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class CancellationController extends Controller
{

    use ReportTrait;

    /**
     * @var CancellationRepository
     */
    protected $repository;

    /**
     * @var HeaderRepository
     */
    protected $headerRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;


    /**
     * CancellationController constructor.
     *
     * @param CancellationRepository $repository
     * @param HeaderRepository       $headerRepository
     */
    public function __construct(
        CancellationRepository $repository,
        HeaderRepository $headerRepository,
        RetailerProductRepository $retailerProductRepository
    ) {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);

        $this->repository       = $repository;
        $this->headerRepository = $headerRepository;
        $this->retailerProductRepository = $retailerProductRepository;
    }

    public function lists(Guard $auth, Request $request, $rp_id) 
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $data = $this->data($auth->user());
            $headers = [];
            $code = $retailerProduct->companyProduct->product->code;

            if ($request->has('_token')) {
                $request->flash();
            }

            $headers = $this->repository->getHeaderList($request);

            return view($retailerProduct->companyProduct->product->code . '.cancellation.list', compact('rp_id', 'headers', 'data', 'code', 'retailerProduct'));
        }
        return redirect()->back();
    }
    
    public function create($rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $header = $this->headerRepository->getModel();

                $payload = view('de.cancellation.create', compact('rp_id', 'header'));

                return response()->json([
                    'payload' => $payload->render()
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

    public function cancel()
    {
        return view('adm.operation.cancellation');
    }


    /**
     * @param Guard   $auth
     * @param Request $request
     * @param string  $rp_id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function dataList(Guard $auth, Request $request, $rp_id)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $data = [];
            $code = '';
            $api  = $this->repository->getHeaderList($request);

            $data = $this->data($auth->user());
            $data['type_terms'] = config('base.term_types');
            $data['products'] = $this->repository->getProducts();

            return response()->json(compact('data', 'api'));
        }

        return redirect()->back();
    }


    /**
     * @param Guard $auth
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function dataAdm(Request $request)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $data = $this->repository->data();
            $api  = $this->repository->getHeaderList($request);

            return response()->json(compact('data', 'api'));
        }

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request, $rp_id, $header_id)
    {
        \Debugbar::disable();

        $this->validate($request, [
            'reason' => 'required|ands_full'
        ]);

        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $header = $this->headerRepository->getModel();

                if ($this->repository->storeCancellation($request, $header)) {
                    $mail           = new MailController($request->user());
                    $mail->subject  = 'Anulacion de Certificado No. ' . $header->prefix . '-' . $header->issue_number;
                    $mail->template = 'de.cancellation';

                    array_push($mail->receivers, [
                        'email' => $header->user->email,
                        'name'  => $header->user->full_name,
                    ]);

                    if ($mail->send(decode($rp_id), [ 'header' => $header ])) {

                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'El Certificado '
                                     . $header->prefix
                                     . '-'
                                     . $header->issue_number
                                     . ' fue anulado correctamente.',
                    ]);
                }
            }

            return response()->json([
                'warning' => true,
                'message' => 'El Certificado no pudo ser anulado.',
            ], 402);
        }

        return redirect()->back();
    }
}
