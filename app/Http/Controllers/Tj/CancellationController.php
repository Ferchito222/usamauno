<?php

namespace Sibas\Http\Controllers\Tj;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Sibas\Http\Controllers\MailController;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\Tj\CancellationRepository;
use Sibas\Repositories\Tj\HeaderRepository;
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
            $data    = $this->data($auth->user());
            $headers = [ ];
            $code    = $retailerProduct->companyProduct->product->code;
            
            if ($request->has('_token')) {
                $request->flash();
            }
            
            $headers = $this->repository->getHeaderList($request);
            
            return view($retailerProduct->companyProduct->product->code.'.cancellation.list', compact('rp_id', 'headers', 'data', 'code', 'retailerProduct'));
        }
        return redirect()->back();
    }
    
    public function create($rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $header = $this->headerRepository->getModel();
                
                $payload = view($retailerProduct->companyProduct->product->code.'.cancellation.create', compact('rp_id', 'header', 'retailerProduct', 'data'));

                return response()->json([
                    'payload' => $payload->render()
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }
    
    public function store(Request $request, $rp_id, $header_id)
    {
        $this->validate($request, [
            'reason' => 'required|ands_full',
        ]);

        $aut = true;

        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $header = $this->headerRepository->getModel();
                
                if ($this->repository->storeCancellation($request, $header)) {
                    $mail = new MailController($request->user());
                    $mail->subject = 'Anulacion de Poliza No. ' . $header->prefix . '-' . $header->issue_number;
                    $mail->template = 'de.cancellation';

                    array_push($mail->receivers, [
                        'email' => $header->user->email,
                        'name' => $header->user->full_name,
                            ], [
                        'email' => $request->user()->email,
                        'name' => $request->user()->full_name,
                    ]);

                    if ($mail->send(decode($rp_id), ['header' => $header])) {
                        
                    }

                    return response()->json([
                                'location' => route($retailerProduct->companyProduct->product->code . '.cancel.lists', ['rp_id' => $rp_id])
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }
}
