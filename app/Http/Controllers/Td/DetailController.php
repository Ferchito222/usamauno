<?php

namespace Sibas\Http\Controllers\Td;

use Illuminate\Support\Facades\Cache;
use Sibas\Entities\Td\Detail;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Td\PropertyEditFormRequest;
use Sibas\Http\Requests\Td\DetailCreateFormRequest;
use Sibas\Http\Requests\Td\DetailEditFormRequest;
use Sibas\Repositories\Retailer\CityRepository;
use Sibas\Repositories\Td\DetailRepository;
use Sibas\Repositories\Td\FacultativeRepository;
use Sibas\Repositories\Td\HeaderRepository;
use Sibas\Repositories\De\DataRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\WsRepository;

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

    /**
     * @var CityRepository
     */
    protected $cityRepository;


    public function __construct(
        DetailRepository $repository,
        HeaderRepository $headerRepository,
        FacultativeRepository $facultativeRepository,
        RetailerProductRepository $retailerProductRepository,
        CityRepository $cityRepository,
        WsRepository $ws,    
        DataRepository $dataRepository
    ) {
        $this->repository                = $repository;
        $this->headerRepository          = $headerRepository;
        $this->dataRepository            = $dataRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->facultativeRepository     = $facultativeRepository;
        $this->cityRepository            = $cityRepository;
        $this->ws                        = $ws;
    }


    private function getData()
    {
        $materia = $this->dataRepository->getPropertyTypes();
        $estado  = $this->dataRepository->getPropertyStates();
        $use  = $this->dataRepository->getPropertyUses();
        
        unset($materia[2]);
        unset($materia[3]);
        unset($materia[4]);
        unset($estado[2]);
        unset($estado[3]);
        unset($use[4]);
        
        return [
            'property_types' => $materia,
            'property_uses'  => $use,
            'cities'         => $this->cityRepository->getCitiesByType(),
            'property_states'=> $estado,
        ];
    }
    
    public function lists($rp_id, $header_id)
    {
        if ($this->headerRepository->getHeaderById(decode($header_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $header          = $this->headerRepository->getModel();
            $retailerProduct = $this->retailerProductRepository->getModel();

            return view('td.lists', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
        }

        return redirect()->back();
    }
    
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
                    if ($this->ws->getWsRest('codigo_catastral', request()->get('nro_folio_real'))) {
                        //if (1) {
                        $result = $this->ws->getData();
                        //$result = dataExmaple('td');;
                        
                        if ($result['statusCode'] != 'MSSE-0000')
                            return response()->json(['err' => $result['errorDetail']], 401);
                        
                        $detail = $this->repository->setDataWs($result, $data);
                        
                    }else
                        return response()->json(['err' => 'Error respuesta ws.'], 402);
                    
                }else{
                    if(request()->get('nro_folio_real') != '' && $this->repository->getDetailByNroFolioReal(request()->get('nro_folio_real')))
                        $detail = $this->repository->getModel();
                    else
                        return response()->json(['err' => 'Unauthorized action.'], 401);
                }
                
                unset($data['cities']['CI'][10]);
                $payload = view('td.risk-create', compact('rp_id', 'header_id', 'header', 'data', 'parameter','detail'));
                
                return response()->json([
                    'payload'       => $payload->render(),
                    'detail'        => $detail,
                    'property_types'=> $data['property_types'],
                    'property_uses'  => $data['property_uses'],
                    'property_states'=> $data['property_states'],
                    'cities'        => $data['cities']['CI'],
                    'amount_max'    => $parameter->amount_max,
                    'currency'      => $detail->currency,
                    'header'        => $header,
                    'exchange_rate' => $exchange_rate,
                ]);
            }
            return response()->json(['err' => 'Unauthorized action.'], 401);
        }
        return redirect()->back();
    }

    public function store(DetailCreateFormRequest $request, $rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id)) && $this->headerRepository->updateCurrency($request->get('currency'))) {
                $header = $this->headerRepository->getModel();
                if($this->repository->verifyCatastralCode($request->get('nro_folio_real'))) {
                    if ($this->repository->storeDetail($request, $header)) {
                        return response()->json([
                            'location' => route('td.mr.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                        ]);
                    }
                } else {
                    return response()->json(['reason' => 'Ya existe un seguro contratado para la misma garantía (Código Catastral). No puede tener dos seguros para la misma garantía'], 428);
                }
            }
            return response()->json(['err' => 'Unauthorized action.'], 401);
        }
        return redirect()->back();
    }

    public function edit($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $detail          = $this->repository->getModel();
                $header          = $detail->header;
                $retailerProduct = $this->retailerProductRepository->getModel();
                $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();
                
                $exchange_rate   = $retailerProduct->retailer->exchangeRate;
                $data            = $this->getData();
                unset($data['cities']['CI'][10]);
                $detail->currency = $header->currency;
                $payload = view('td.risk-edit',
                    compact('rp_id', 'header_id', 'detail_id', 'header', 'data', 'parameter','detail'));
                
                return response()->json([
                    'payload'       => $payload->render(),
                    'detail'        => $detail,
                    'property_types'=> $data['property_types'],
                    'property_uses' => $data['property_uses'],
                    'property_states' => $data['property_states'],
                    'cities'        => $data['cities']['CI'],
                    'amount_max'    => $parameter->amount_max,
                    'currency'      => $header->currency,
                    'header'        => $header,
                    'exchange_rate' => $exchange_rate,
                ]);
            }
            return response()->json(['err' => 'Unauthorized action.'], 401);
        }
        return redirect()->back();
    }

    public function update(DetailCreateFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id))) {
                if($this->repository->verifyCatastralCode($request->get('nro_folio_real'))) {
                    if ($this->repository->updateDetail($request)) {
                        return response()->json([
                            'location' => route('td.mr.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                        ]);
                    }
                } else {
                    return response()->json(['reason' => 'Ya existe un seguro contratado para la misma garantía (Código Catastral). No puede tener dos seguros para la misma garantía'], 428);
                }
            }
            return response()->json(['err' => 'Unauthorized action.'], 401);
        }
        return redirect()->back();
    }
    
    public function destroy($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->repository->removeDetail()) {
                return response()->json([
                    'location' => route('td.mr.lists', ['rp_id' => $rp_id, 'header_id' => $header_id]),
                ]);
            }
            return response()->json(['err' => 'Unauthorized action.'], 401);
        }
        return redirect()->back();
    }
    
    public function editIssuance($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $detail          = $this->repository->getModel();
                $header          = $detail->header;
                $retailerProduct = $this->retailerProductRepository->getModel();
                $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();
                
                $exchange_rate   = $retailerProduct->retailer->exchangeRate;
                $data            = $this->getData();
                
                $payload = view('td.risk-edit-issuance',
                    compact('rp_id', 'header_id', 'detail_id', 'header', 'detail', 'parameter'));
                
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

    public function updateIssuance(DetailEditFormRequest $request, $rp_id, $header_id, $detail_id = null)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();

                if ($this->repository->getDetailById(decode($detail_id))) {

                    if ($this->repository->updateRiskIssuance($request)) {
                        $detail = $this->repository->getModel();
                        
                        $facultative = $this->facultativeRepository->roleFacultative(decode($rp_id), decode($header_id), $detail->header);
                        
                        //if ($facultative['facultative'] === 0) {
                        if (true) {
                            if ($detail->header->facultative === true) {
                                $this->repository->deleteFacultativeHeader(decode($header_id));
                            }
                        }else{
                            
                            $this->facultativeRepository->storeFacultative($facultative, $request->user());
                            if($this->headerRepository->getHeaderById(decode($header_id))){
                                $header = $this->headerRepository->getModel();
                                foreach ($header->details as $key => $value) {
                                    if ( ! in_array($value->id, $this->facultativeRepository->idsFactultative)) {
                                        $this->repository->delFacultativeById($value->id);
                                    }
                                }
                                $obs = $this->facultativeRepository->returnObservation();
                                $this->headerRepository->updateFacultativeHeader(true, $obs);
                            }
                        }
                        return response()->json([
                                        'location' => route('td.edit', [
                                            'rp_id' => $rp_id,
                                            'header_id' => $header_id,
                                            $request->get('idf') ? 'idf=' . $request->get('idf') : null,
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
