<?php

namespace Sibas\Http\Controllers\Ema;

use Illuminate\Support\Facades\Cache;
use Sibas\Entities\Ema\Detail;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Ema\DetailCreateFormRequest;
use Sibas\Http\Requests\Ema\DetailEditFormRequest;
use Sibas\Repositories\Ema\DetailRepository;
use Sibas\Repositories\Ema\FacultativeRepository;
use Sibas\Repositories\Ema\HeaderRepository;
use Sibas\Repositories\De\DataRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\WsRepository;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class DetailController extends Controller
{

    /**
     * @var DetailRepository
     */
    protected $repository;
    
    /**
     * @var WsRepository
     */
    protected $ws;

    /**
     * @var HeaderRepository
     */
    protected $headerRepository;

    /**
     * @var DataRepository
     */
    protected $dataRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var FacultativeRepository
     */
    protected $facultativeRepository;


    public function __construct(
        DetailRepository $repository,
        HeaderRepository $headerRepository,
        FacultativeRepository $facultativeRepository,
        RetailerProductRepository $retailerProductRepository,
        WsRepository $ws,
        DataRepository $dataRepository
    ) {
        $this->repository                = $repository;
        $this->headerRepository          = $headerRepository;
        $this->dataRepository            = $dataRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->facultativeRepository     = $facultativeRepository;
        $this->ws                        = $ws;
    }


    private function getData()
    {
        return [];
    }

    /**
     * 
     * @param type $rp_id
     * @param type $header_id
     * @return type
     */
    public function lists($rp_id, $header_id)
    {
        if ($this->headerRepository->getHeaderById(decode($header_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $header          = $this->headerRepository->getModel();
            $retailerProduct = $this->retailerProductRepository->getModel();

            return view('ema.lists', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
        }

        return redirect()->back();
    }

    /**
     * form create detail
     * @param type $rp_id
     * @param type $header_id
     * @return type
     */
    public function create($rp_id, $header_id)
    { 
        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $header          = $this->headerRepository->getModel();
                $retailerProduct = $this->retailerProductRepository->getModel();
                $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();
                
                $exchange_rate   = $retailerProduct->retailer->exchangeRate;
                $data            = $this->getData();
                
                if($retailerProduct->ws){
                    if ($this->ws->getWsRest('numero_serie', request()->get('serial_number'))) {
                        //if (1) {
                        $result = $this->ws->getData();
                        //$result = dataExmaple('ema');
                        
                        if ($result['statusCode'] != 'MSSE-0000')
                            return response()->json(['err' => $result['errorDetail']], 401);
                        
                        $detail = $this->repository->setDataWs($result, $data);
                        
                    }else
                        return response()->json(['err' => 'Error respuesta ws.'], 402);
                }else{
                    if(request()->get('id') != '' && $this->repository->getDetailById(request()->get('id')))
                        $detail = $this->repository->getModel();
                    else
                        return response()->json(['err' => 'Unauthorized action.'], 401);
                }
                
                $payload = view('ema.detail-create', compact('rp_id', 'header_id', 'header', 'data', 'parameter', 'detail'));
                
                return response()->json([
                    'payload'       => $payload->render(),
                    'detail'        => $detail,
                    'amount_max'    => $parameter->amount_max,
                    'currency'      => $detail->currency,
                    'exchange_rate' => $exchange_rate,
                ]);
        }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }

    /**
     * store detail
     * @param DetailCreateFormRequest $request
     * @param type $rp_id
     * @param type $header_id
     * @return type
     */
    public function store(DetailCreateFormRequest $request, $rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id)) && $this->headerRepository->updateCurrency($request->get('currency'))) {
                $header = $this->headerRepository->getModel();
                if($this->repository->verifySerialNumber($request->get('serial_number'))) {
                    if ($this->repository->store($request, $header)) {
                        return response()->json([
                                    'location' => route('ema.detail.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                        ]);
                    }
                } else {
                    return response()->json(['reason' => 'Ya existe un seguro contratado para la misma garantía (Número de Serie). No puede tener dos seguros para la misma garantía'], 428);
                }
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }

    /**
     * delete detail
     * @param type $rp_id
     * @param type $header_id
     * @param type $detail_id
     * @return type
     */
    public function destroy($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->repository->remove()) {
                return response()->json([
                    'location' => route('ema.detail.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                ]);
            }
            return response()->json(['err' => 'Unauthorized action.'], 401);
        }
        return redirect()->back();
    }
    
    /**
     * form edit
     * @param type $rp_id
     * @param type $header_id
     * @param type $detail_id
     * @return type
     */
    public function edit($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $detail          = $this->repository->getModel();
                $header          = $detail->header;
                $retailerProduct = $this->retailerProductRepository->getModel();
                $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();
               
                $exchange_rate   = $retailerProduct->retailer->exchangeRate;

                $data = $this->getData();
                $detail->currency = $header->currency;
                $payload = view('ema.detail-edit',
                    compact('rp_id', 'header_id', 'detail_id', 'header', 'data', 'parameter', 'detail'));

                return response()->json([
                    'payload'       => $payload->render(),
                    'detail'        => $detail,
                    'amount_max'    => $parameter->amount_max,
                    'currency'      => $header->currency,
                    'exchange_rate' => $exchange_rate,
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }

    /**
     * update
     * @param DetailCreateFormRequest $request
     * @param type $rp_id
     * @param type $header_id
     * @param type $detail_id
     * @return type
     */
    public function update(DetailCreateFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id))) {
                if($this->repository->verifySerialNumber($request->get('serial_number'))) {
                    if ($this->repository->update($request)) {
                        return response()->json([
                            'location' => route('ema.detail.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                        ]);
                    }
                } else {
                    return response()->json(['reason' => 'Ya existe un seguro contratado para la misma garantía (Número de Serie). No puede tener dos seguros para la misma garantía'], 428);
                }
            }
            return response()->json(['err' => 'Unauthorized action.'], 401);
        }
        return redirect()->back();
    }

    /**
     * form edit detail issuance
     * @param type $rp_id
     * @param type $header_id
     * @param type $detail_id
     * @return type
     */
    public function editIssuance($rp_id, $header_id, $detail_id = null)
    {
        if (request()->ajax()) {
            $flag   = false;
            $header = null;
            $detail = null;

            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();
                $exchange_rate   = $retailerProduct->retailer->exchangeRate;

                $data = $this->getData();

                if ($this->repository->getDetailById(decode($detail_id))) {
                    $detail = $this->repository->getModel();
                    $header = $detail->header;

                    $flag = true;
                } elseif (Cache::has(decode($header_id)) && request()->has('coverage') && $this->headerRepository->getHeaderById(decode($header_id))) {
                    $detail = new Detail();
                    $header = $this->headerRepository->getModel();

                    $flag = true;
                }

                if ($flag) {
                    $payload = view('ema.detail-edit-issuance',
                        compact('rp_id', 'header_id', 'detail_id', 'header', 'data', 'parameter'));

                    return response()->json([
                        'payload'       => $payload->render(),
                        'detail'        => $detail,
                        'amount_max'    => $parameter->amount_max,
                        'exchange_rate' => $exchange_rate,
                        'currency'      => $header->currency,
                    ]);
                }
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }

    /**
     * udpate detail issuance
     * @param DetailEditFormRequest $request
     * @param type $rp_id
     * @param type $header_id
     * @param type $detail_id
     * @return type
     */
    public function updateIssuance(DetailEditFormRequest $request, $rp_id, $header_id, $detail_id = null)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();

                if ($this->repository->getDetailById(decode($detail_id))) {
                    if ($this->repository->verifySerialNumber($request->get('serial_number')) && $this->repository->updateDetailIssuance($request)) {
                        $detail = $this->repository->getModel();

                        if (Cache::has(decode($header_id)) && $request->has('coverage')) {
                            goto StoreVehicle;
                        }

                        //if ($this->facultativeRepository->storeFacultative($detail, $retailerProduct,$request->user())) {
                            //$this->headerRepository->setHeaderFacultative(decode($header_id));
                            return response()->json([
                                'location' => route('ema.edit', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id,
                                    $request->get('idf') ? 'idf=' . $request->get('idf') : null,
                                ]),
                            ]);
                        //}
                    } else {
                        return response()->json(['reason' => 'Ya existe un seguro contratado para la misma garantía (Número de Serie). No puede tener dos seguros para la misma garantía'], 428);
                    }
                } elseif (Cache::has(decode($header_id)) && $request->has('coverage') && $this->headerRepository->getHeaderById(decode($header_id))) {
                    $header = $this->headerRepository->getModel();

                    if ($this->repository->storeVehicle($request, $header, true)) {
                        $detail = $this->repository->getModel();

                        StoreVehicle:
                        $sf = $this->facultativeRepository->storeFacultative($detail, $retailerProduct,
                            $request->user(), true);

                        if ($sf === 428) {
                            $errors = $this->facultativeRepository->getErrors();

                            return response()->json(['reason' => $errors['reason']], 428);
                        }

                        return response()->json([
                            'location' => route('ema.coverage.edit', [
                                'rp_id'     => $rp_id,
                                'de_id'     => $request->get('coverage'),
                                'header_id' => $header_id,
                            ]),
                        ]);

                    }
                }
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }
    
}
