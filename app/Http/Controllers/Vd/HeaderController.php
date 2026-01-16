<?php

namespace Sibas\Http\Controllers\Vd;

use Illuminate\Auth\Guard;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Sibas\Entities\Vd\Header;
use Sibas\Entities\Client;
use Sibas\Entities\ProductParameter;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Controllers\MailController;
use Sibas\Http\Requests;
use Sibas\Http\Requests\Vd\HeaderFormRequest;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\Vd\HeaderRepository;
use Sibas\Repositories\Vd\DetailRepository;
use Sibas\Repositories\Vd\CollectionRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\WsRepository;
use Sibas\Repositories\WsLBCRepository;

class HeaderController extends Controller
{
    /**
     * @var HeaderRepository
     */
    protected $repository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var DetailRepository
     */
    protected $detailRepository;

    /**
     * @var PolicyRepository
     */
    protected $policyRepository;

    /**
     * @var CollectionRepository
     */
    protected $collectionRepository;

    /**
     * @var WsRepository
     */
    protected $wsRepository;
    
    /**
     * @var WsLBCRepository
     */
    protected $wsLBCRepository;

    public function __construct(
        HeaderRepository $repository,
        RetailerProductRepository $retailerProductRepository,
        ClientRepository $clientRepository,
        DetailRepository $detailRepository,
        PolicyRepository $policyRepository,
        CollectionRepository $collectionRepository,
        WsRepository $wsRepository,
        WsLBCRepository $wsLBCRepository
    ) {
        $this->repository                   = $repository;
        $this->retailerProductRepository    = $retailerProductRepository;
        $this->clientRepository             = $clientRepository;
        $this->detailRepository             = $detailRepository;
        $this->policyRepository             = $policyRepository;
        $this->collectionRepository         = $collectionRepository;
        $this->wsRepository                 = $wsRepository;
        $this->wsLBCRepository              = $wsLBCRepository;
    }

    use DataTrait;

    public function lists($rp_id, $header_id)
    {
        $header = null;
        
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {

            if ($this->repository->getHeaderById(decode($header_id))) {
                $header = $this->repository->getModel();
                
                $user            = auth()->user();
                $retailerProduct = $this->retailerProductRepository->getModel();
                
                $parameter       = $retailerProduct->parameters->filter(function ($item) {
                    return $item->slug === 'GE';
                })->first();
                
                $coverage_detail = ($parameter->detail)?$parameter->detail:0;
                
            }

            return view($retailerProduct->companyProduct->product->code.'.list',
                compact('rp_id', 'header_id', 'header', 'coverage_detail', 'user', 'retailerProduct'));
        }

        return redirect()->back();
    }

    public function result($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();

            if ($this->repository->getHeaderById(decode($header_id)) && $this->repository->setRetailerProduct($retailerProduct)) {
                $header    = $this->repository->getModel();
                $user      = request()->user();
                #actualiza tasas y primas

                return view($retailerProduct->companyProduct->product->code.'.result', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
            }
        }

        return redirect()->back()->with([ 'error_header' => 'La tasa no pudo ser registrada' ]);
    }

    public function edit($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->repository->getModel();
            
            $data = $this->getData($rp_id);
            
            unset($data['payment_methods'][2]);
            unset($data['payment_methods'][3]);
            unset($data['payment_methods'][4]);
            unset($data['payment_methods'][5]);

            $data['policies'] = $this->policyRepository->getPolicyForIssuance(decode($rp_id), $header->currency);
            // dd("DATA: ", $data);
            
            return view($retailerProduct->companyProduct->product->code.'.edit', compact('rp_id', 'header_id', 'header', 'data', 'retailerProduct'));
        }

        return redirect()->back();
    }

    public function update(HeaderFormRequest $request, $rp_id, $header_id)
    {
        // dd("Estamos actualziando todo...", $this->retailerProductRepository->getRetailerProductById(decode($rp_id)), $this->repository->getHeaderById(decode($header_id)));
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header = $this->repository->getModel();
            
            if($request->get('payment_method') == 'TJ'){
                $prefix = 'VITC';
            }else {
                $prefix = strtoupper($retailerProduct->companyProduct->product->code);
            }
            $issue_number = $this->repository->getNumberPrefix($prefix,'I');

            $policy_number = $this->repository->getPolicyNumber($issue_number, $prefix);

            #ws registro orden de cobro
            if ($request->get('payment_method') == 'CO') {
                // $ws = $retailerProduct->ws;
                $ws = false;
                $flag = 1;

                if($flag){
                    $data = '';
                    if ($data == '')
                        true;
                    else
                        return redirect()->back()->with(['error_header' => 'No se realizo el registro de cobro.',])->withInput()->withErrors($this->repository->getErrors());
                } else
                    return redirect()->back()->with(['error_header' => 'Error WS: ' . $this->wsRepository->getMessage(),])->withInput()->withErrors($this->repository->getErrors());
            }
            
            if($header->details->first()->beneficiaries->count() > 0){
                // dd("Pasamos: ", $request->all(), decode($rp_id), decode($header_id), $retailerProduct, $header, $header->details->first()->beneficiaries->count(), $this->repository->updateHeader($request, $retailerProduct));
                if ($this->repository->updateHeader($request, $retailerProduct)) {
                    $header = $this->repository->getModel();
                    
                    if ($this->detailRepository->getDetailById($header->details->first()->id) && $this->detailRepository->updateAccount($request)) {
                        $detail = $this->detailRepository->getModel();

                        return redirect()->route($retailerProduct->companyProduct->product->code . '.edit', [
                                    'rp_id' => $rp_id,
                                    'header_id' => $header_id,
                                ])->with(['success_header' => 'La Póliza fue actualizada con éxito.']);
                    }
                }
            }else
                return redirect()->back()->with(['error_header' => 'Debe registrar al menos un beneficiario',])->withInput()->withErrors($this->repository->getErrors());
        }
        return redirect()->back()->withInput();
    }

    public function issuance($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header = $this->repository->getModel();
            $cobro = 0;
            $ws = $retailerProduct->ws;
            $lbc = '';
            $lbc_collection = '';
            //$ws = false;
            #ws Verificar orden de corbo
            if($header->payment_method == 'CO') {
                $fco = 1;
                if($fco){
                    $cobro = 1;
                } else
                    return redirect()->back()->with(['error_header' => 'Error WS: ' . $this->wsRepository->getMessage(),])->withInput()->withErrors($this->repository->getErrors());
            }

            $titular = $header->details->first();
            $parameter       = $retailerProduct->parameters->filter(function ($item) {
                return $item->slug === 'GE';
            })->first();
            $birthdate = date("d/m/Y", strtotime($titular->client->birthdate));

            if ($this->detailRepository->checkAgeByParameter($birthdate, $parameter)) {
                $this->clientRepository->getClientByDni($titular->client->dni, $titular->client->extension);
                $client = $this->clientRepository->getModel();

                if($client->count() > 0)
                    if ($client->detailsVd)
                        $key = $this->repository->checkQuantityProducts($client->detailsVd, $retailerProduct->companyProduct->product->code, $titular->client->dni, $titular->client->extension, $client->detailsVd->first()->header->plan->name);
                    else
                        $key = true;
                else
                    $key = true;

                if ($key) {
                    if ($this->repository->issuanceHeader()) {
                        $this->collectionRepository->storeCollections($header, $cobro);

                        $lbc = ($ws) ? $this->wsLBCRepository->registerProductonVIAR($header, 'register', 'POST', '') : 1;
                        if($lbc)
                            $data_lbc = ($ws) ? $this->wsLBCRepository->getData() : getProductonVIAR();  
                            if($data_lbc['EsValido']=='true'){                                                      
                                $lbc_collection = ($ws) ? $this->wsLBCRepository->registerPagoVIAR($header, 'register', 'POST', '') : 1;
                                if($lbc_collection)
                                    $data_lbc_collections = ($ws) ? $this->wsLBCRepository->getDataRecords() : getPagoVIAR();
                            }
                            //dd($data_lbc_collections);                        
                        return redirect()->route($retailerProduct->companyProduct->product->code.'.show.issuance', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                        ])->with([ 'success_header' => 'La Póliza fue emitida con éxito. ' ]);
                    }
                }else{
                    return redirect()->back()->with([
                        'error_header' => 'El cliente sobrepasa el limite de seguros de este producto',
                    ])->withInput()->withErrors($this->detailRepository->getErrors());
                }
            } else {
                ClientAgeErr:
                return redirect()->back()->with([
                    'error_header' => 'El Cliente no cumple con el rango de edades [ '
                        . $parameter->age_min . ' - '
                        . $parameter->age_max . ' ]',
                ])->withInput()->withErrors($this->detailRepository->getErrors());
            }
        }

        return redirect()->back();
    }

    public function showIssuance($rp_id, $header_id)
    {
        if($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))){
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header = $this->repository->getModel();
            
            if ($header->issued) 
                return view($retailerProduct->companyProduct->product->code.'.issuance', compact('rp_id', 'header_id', 'header','retailerProduct'))
                    ->with([ 'success_header' => 'La Póliza fue emitida con éxito.' ]);
        }
        return redirect()->back();
    }
}