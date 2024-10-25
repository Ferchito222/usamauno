<?php

namespace Sibas\Http\Controllers\Au;

use Illuminate\Support\Facades\Cache;
use Sibas\Entities\Au\Detail;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Au\VehicleCreateFormRequest;
use Sibas\Http\Requests\Au\VehicleEditFormRequest;
use Sibas\Repositories\Au\DetailRepository;
use Sibas\Repositories\Au\FacultativeRepository;
use Sibas\Repositories\Au\HeaderRepository;
use Sibas\Repositories\Au\VehicleMakeRepository;
use Sibas\Repositories\Au\VehicleTypeRepository;
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
     * @var VehicleTypeRepository
     */
    protected $vehicleTypeRepository;

    /**
     * @var DataRepository
     */
    protected $dataRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var VehicleMakeRepository
     */
    protected $vehicleMakeRepository;

    /**
     * @var FacultativeRepository
     */
    protected $facultativeRepository;


    public function __construct(
        DetailRepository $repository,
        HeaderRepository $headerRepository,
        FacultativeRepository $facultativeRepository,
        RetailerProductRepository $retailerProductRepository,
        VehicleTypeRepository $vehicleTypeRepository,
        VehicleMakeRepository $vehicleMakeRepository,
        WsRepository $ws,
        DataRepository $dataRepository
    ) {
        $this->repository                = $repository;
        $this->headerRepository          = $headerRepository;
        $this->vehicleTypeRepository     = $vehicleTypeRepository;
        $this->vehicleMakeRepository     = $vehicleMakeRepository;
        $this->dataRepository            = $dataRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->facultativeRepository     = $facultativeRepository;
        $this->ws                        = $ws;
    }


    private function getData()
    {
        return [
            'vehicle_types'      => $this->vehicleTypeRepository->getVehicleType(),
            'vehicle_categories' => $this->dataRepository->getVehicleCategory(),
            'vehicle_uses'       => $this->dataRepository->getVehicleUse(),
            'vehicle_makes'      => $this->vehicleMakeRepository->getVehicleMakes(),
        ];
    }


    /**
     * Lists Detail vehicle
     *
     * @param $rp_id
     * @param $header_id
     *
     * @return mixed
     */
    public function lists($rp_id, $header_id)
    {
        if ($this->headerRepository->getHeaderById(decode($header_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $header          = $this->headerRepository->getModel();
            $retailerProduct = $this->retailerProductRepository->getModel();

            return view('au.lists', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
        }

        return redirect()->back();
    }
    
    public function test_ws()
    { 
        if (request()->get('type') && request()->get('param')) {
            
            if ($this->ws->getWsRest(request()->get('type'), request()->get('param'))) {
                $result = $this->ws->getData();
                dd($result);
            }else{
                echo "<br />---error WS-->";
                exit();
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $rp_id
     * @param $header_id
     *
     * @return mixed
     */
    public function create($rp_id, $header_id)
    { 
        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $header          = $this->headerRepository->getModel();
                $retailerProduct = $this->retailerProductRepository->getModel();
                $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();
                $categories      = $retailerProduct->categories()->where('active', true)->orderBy('category',
                    'ASC')->where('category','!=','CC')->get();

                $exchange_rate   = $retailerProduct->retailer->exchangeRate;
                $data            = $this->getData();
                
                if($retailerProduct->ws){
                    $flag = (request()->get('type_data')=='ruat')?'codigo_'.request()->get('type_data'):request()->get('type_data');
                    if ($this->ws->getWsRest($flag, request()->get('nro_ruat'))) {
                        //if (1) {
                        $result = $this->ws->getData();
                        //$result = dataExmaple('au');
                        
                        if ($result['statusCode'] != 'MSSE-0000')
                            return response()->json(['err' => $result['errorDetail']], 401);
                        
                        $detail = $this->repository->setDataWs($result, $data);

                    }else
                        return response()->json(['err' => 'Error respuesta ws.'], 402);
                }else{
                    if(request()->get('nro_ruat') != '' && $this->repository->getDetailByRuat(request()->get('nro_ruat')))
                        $detail = $this->repository->getModel();
                    else
                        return response()->json(['err' => 'Unauthorized action.'], 401);
                }
                
                $payload = view('au.vehicle-create', compact('rp_id', 'header_id', 'header', 'data', 'parameter', 'detail'));
                
                return response()->json([
                    'payload'       => $payload->render(),
                    'detail'        => $detail,
                    'types'         => $data['vehicle_types'],
                    'categories'    => $categories,
                    'amount_max'    => $parameter->amount_max,
                    'amount_max_ca'    => $parameter->amount_max_ca,
                    'amount_max_cb'    => $parameter->amount_max_cb,
                    'currency'      => $detail->currency,
                    'exchange_rate' => $exchange_rate,
                ]);
        }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param VehicleCreateFormRequest $request
     * @param string                   $rp_id
     * @param string                   $header_id
     *
     * @return mixed
     */
    public function store(VehicleCreateFormRequest $request, $rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id)) && $this->headerRepository->updateCurrency($request->get('currency')) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $retailerProduct    = $this->retailerProductRepository->getModel();
                $header             = $this->headerRepository->getModel();
                
                if($this->repository->amountMaxAndYearMaxVerified($request, $retailerProduct, $request->get('category')['category'], $request->get('insured_value'), $request->get('currency'), $request->get('year')))
                    return response()->json(['reason' => $this->repository->getMessageOld()], 428);

                if($this->repository->verifyEngineChassis($request->get('engine'), $request->get('chassis'))) {
                    if ($this->repository->storeVehicle($request, $header)) {
                        return response()->json([
                                    'location' => route('au.vh.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                        ]);
                    }
                } else {
                    return response()->json(['reason' => 'Ya existe un seguro contratado para la misma garantía. No puede tener dos seguros para la misma garantía'], 428);
                }
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $rp_id
     * @param string $header_id
     * @param string $detail_id
     *
     * @return mixed
     */
    public function destroy($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->repository->removeVehicle()) {
                return response()->json([
                    'location' => route('au.vh.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param string $rp_id
     * @param string $header_id
     * @param string $detail_id
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     *
     */
    public function edit($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $detail          = $this->repository->getModel();
                $header          = $detail->header;
                $retailerProduct = $this->retailerProductRepository->getModel();
                $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();
                $categories      = $retailerProduct->categories()->where('active', true)->orderBy('category',
                    'ASC')->where('category','!=','CC')->get();
                $exchange_rate   = $retailerProduct->retailer->exchangeRate;

                $data = $this->getData();

                $detail->currency = $header->currency;
                $payload = view('au.vehicle-edit',
                    compact('rp_id', 'header_id', 'detail_id', 'header', 'data', 'parameter', 'detail'));

                return response()->json([
                    'payload'       => $payload->render(),
                    'detail'        => $detail,
                    'types'         => $data['vehicle_types'],
                    //'makes'         => $data['vehicle_makes'],
                    'categories'    => $categories,
                    'year_max'      => date('Y') - $parameter->old_car,
                    'amount_max'    => $parameter->amount_max,
                    'amount_max_ca'    => $parameter->amount_max_ca,
                    'amount_max_cb'    => $parameter->amount_max_cb,
                    'currency'      => $header->currency,
                    'exchange_rate' => $exchange_rate,
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VehicleCreateFormRequest $request
     * @param string                   $rp_id
     * @param string                   $header_id
     * @param string                   $detail_id
     */
    public function update(VehicleCreateFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                
                if($this->repository->amountMaxAndYearMaxVerified($request, $retailerProduct, $request->get('category')['category'], $request->get('insured_value'), $request->get('currency'), $request->get('year')))
                    return response()->json(['reason' => $this->repository->getMessageOld()], 428);
                
                if ($this->repository->updateVehicle($request)) {
                    return response()->json([
                        'location' => route('au.vh.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                    ]);
                }
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }

    /**
     * @param string $rp_id
     * @param string $header_id
     * @param string $detail_id
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
                $categories      = $retailerProduct->categories()->where('active', true)->orderBy('category',
                    'ASC')->get();
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
                    $payload = view('au.vehicle-edit-issuance',
                        compact('rp_id', 'header_id', 'detail_id', 'header', 'data', 'parameter'));

                    return response()->json([
                        'payload'       => $payload->render(),
                        'detail'        => $detail,
                        'types'         => $data['vehicle_types'],
                        //'makes'         => $data['vehicle_makes'],
                        'categories'    => $categories,
                        'year_max'      => date('Y') - $parameter->old_car,
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
     * Update the specified resource in storage.
     *
     * @param VehicleEditFormRequest $request
     * @param string                 $rp_id
     * @param string                 $header_id
     * @param string                 $detail_id
     *
     * @return
     */
    public function updateIssuance(VehicleEditFormRequest $request, $rp_id, $header_id, $detail_id = null)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();

                if ($this->repository->getDetailById(decode($detail_id))) {
                    if ($this->repository->verifyEngineChassis($request->get('engine'),$request->get('chassis')) && $this->repository->updateVehicleIssuance($request)) {
                        $detail = $this->repository->getModel();

                        if (Cache::has(decode($header_id)) && $request->has('coverage')) {
                            goto StoreVehicle;
                        }

                        if ($this->facultativeRepository->storeFacultative($detail, $retailerProduct,
                            $request->user())
                        ) {
                            $this->headerRepository->setHeaderFacultative(decode($header_id));

                            return response()->json([
                                'location' => route('au.edit', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id,
                                    $request->get('idf') ? 'idf=' . $request->get('idf') : null,
                                ]),
                            ]);
                        }
                    } else {
                        return response()->json(['reason' => 'Ya existe un seguro contratado para la misma garantía. No puede tener dos seguros para la misma garantía'], 428);
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
                            'location' => route('au.coverage.edit', [
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
