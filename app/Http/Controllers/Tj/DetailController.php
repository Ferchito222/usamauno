<?php

namespace Sibas\Http\Controllers\Tj;

use Illuminate\Http\Request;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Entities\Client;
use Sibas\Entities\ProductParameter;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Http\Requests\Tj\ClientCreateFormRequest;
use Sibas\Http\Requests\Tj\ClientComplementFormRequest;
use Sibas\Repositories\Tj\DetailRepository;
use Sibas\Repositories\Tj\HeaderRepository;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\WsRepository;

class DetailController extends Controller
{
    /**
     * @var DetailRepository
     */
    private $repository;
    
    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;
    
    /**
     * @var HeaderRepository
     */
    private $headerRepository;
    
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    
    /**
     * @var WsRepository
     */
    private $wsRepository;

    public function __construct(
            DetailRepository $repository,
            RetailerProductRepository $retailerProductRepository,
            HeaderRepository $headerRepository,
            ClientRepository $clientRepository,
            WsRepository $wsRepository
    )
    {
        $this->repository = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->headerRepository = $headerRepository;
        $this->clientRepository = $clientRepository;
        $this->wsRepository = $wsRepository;
    }
    
    use DataTrait;
    
    public function create($rp_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct    = $this->retailerProductRepository->getModel();
            $data               = $this->getData($rp_id);
            
            unset($data['account_number_types'][0]);
            
            $client             = new Client();

            if (session('client') && session('client') instanceof Client)
                $client = session('client');
            
            return view($retailerProduct->companyProduct->product->code.'.create', compact('rp_id', 'data', 'client', 'retailerProduct'));
        }
        return redirect()->back();
    }
    
    public function store(ClientCreateFormRequest $request, $rp_id)
    {
        if($this->headerRepository->getHeaderByCardExists($request->get('card'), $request->get('code'), $request->get('card_number')))
                return redirect()->back()->with(['error_client' => 'Ya existe un Seguro vigente para este numero de tarjeta.'])->withInput()->withErrors($this->repository->getErrors());
                
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $parameter       = $retailerProduct->parameters->filter(function ($item) {
                return $item->slug === 'GE';
            })->first();
            
            if ($this->headerRepository->storeHeader($request)) {
                $request['header'] = $this->headerRepository->getModel();
                
                if ($parameter instanceof ProductParameter) {
                    if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                        if ($this->clientRepository->createClient($request)) {
                            $request['client'] = $this->clientRepository->getModel();

                            if ($this->repository->createDetail($request)) {
                                $detail = $this->repository->getModel();
                                
                                return redirect()->route($retailerProduct->companyProduct->product->code.'.client.list', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => encode($request['header']->id),
                                ])->with([
                                    'success_client' => 'La información del Cliente fueron registrados',
                                ]);
                            }else
                                return redirect()->back()->with([
                            'error_client' => 'Error datos de tarjeta no coincide [codigo de cliente y Nro de tarjeta] WebServise, consulte con el administrador del sistema',
                        ])->withInput()->withErrors($this->repository->getErrors());
                        }
                    } else {
                        ClientAgeErr:
                        return redirect()->back()->with([
                            'error_client' => 'El Cliente no cumple con el rango de edades [ '
                                              . $parameter->age_min . ' - '
                                              . $parameter->age_max . ' ]',
                        ])->withInput()->withErrors($this->repository->getErrors());
                    }
                }
            }
        }

        return redirect()->back()->with([ 'error_detail' => 'El Cliente no pudo ser registrado' ])->withInput()
            ->withErrors($this->repository->getErrors());
    }
    
    public function edit($rp_id, $header_id, $detail_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))
            && $this->repository->getDetailById(decode($detail_id))) {
            $retailerProduct    = $this->retailerProductRepository->getModel();
            $header             = $this->headerRepository->getModel();
            $detail             = $this->repository->getModel();
            
            if ($detail->client instanceof Client) {
                $client = $detail->client;

                ClientEdit:
                $data   = $this->getData($rp_id);

                return view('client.'.$retailerProduct->companyProduct->product->code.'.edit',
                    compact('rp_id', 'header_id', 'detail_id', 'header', 'detail', 'data', 'client', 'retailerProduct'));
            }
        }

        return redirect()->back()->with([ 'error_client_edit' => 'El Cliente no existe' ]);
    }
    
    public function update(ClientCreateFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $parameter       = $retailerProduct->parameters->filter(function ($item) {
                return $item->slug === 'GE';
            })->first();

            if ($this->headerRepository->getHeaderById(decode($header_id))
                && $this->repository->getDetailById(decode($detail_id))) {
                $request[ 'header' ] = $this->headerRepository->getModel();
                $detail              = $this->repository->getModel();

                if ($parameter instanceof ProductParameter) {
                    if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                        if ($this->clientRepository->editClient($request, $detail->client)) {
                                ClientUpdate:
                                return redirect()->route($retailerProduct->companyProduct->product->code.'.client.list', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id
                                ])->with([ 'success_client' => 'La información del Cliente se actualizó correctamente' ]);
                        }
                    } else {
                        ClientAgeErr:
                        return redirect()->back()->with([
                            'error_client' => 'El Cliente no cumple con el rango de edades [ '
                                              . $parameter->age_min . ' - '
                                              . $parameter->age_max . ' ]',
                        ])->withInput()->withErrors($this->repository->getErrors());
                    }
                }
            }
        }

        return redirect()->back()
            ->with([ 'error_client_edit' => 'La información del Cliente no puede ser actualizada' ])->withInput()
            ->withErrors($this->repository->getErrors());
    }

    public function editIssue($rp_id, $header_id, $detail_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header = $this->headerRepository->getModel();

            if ($this->repository->getDetailById(decode($detail_id))) {
                $detail = $this->repository->getModel();

                $data = $this->getData($rp_id);
                $client = $detail->client;

                if ($client instanceof Client) {
                    return view('client.'.$retailerProduct->companyProduct->product->code.'.edit-issue', 
                            compact('rp_id', 'header_id', 'detail_id', 'retailerProduct', 'data', 'client'));
                }
            }
        }

        return redirect()->back()->with([ 'error_client' => 'La información del Cliente no puede ser editada' ]);
    }
    
    public function updateIssue(ClientComplementFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))
            && $this->repository->getDetailById(decode($detail_id))
        ) {
            $request['header'] = $this->headerRepository->getModel();
            $detail            = $this->repository->getModel();
            $retailerProduct = $this->retailerProductRepository->getModel();

            if (( $detail->client instanceof Client )
                && $this->clientRepository->updateIssueClient($request, $detail->client)
            ) {
                    return redirect()->route($retailerProduct->companyProduct->product->code.'.edit', [
                        'rp_id'     => $rp_id,
                        'header_id' => $header_id
                    ])->with([ 'success_client' => 'La información del Cliente se actualizó correctamente' ]); 
            }
        }

        return redirect()->back()->with([ 'error_client' => 'La información del Cliente no pudo ser actualizada' ])->withInput()->withErrors($this->repository->getErrors());
    }

}
