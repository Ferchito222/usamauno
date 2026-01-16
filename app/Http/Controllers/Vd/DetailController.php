<?php

namespace Sibas\Http\Controllers\Vd;

use Illuminate\Http\Request;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Entities\Client;
use Sibas\Entities\ProductParameter;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Http\Requests\Vd\ClientCreateFormRequest;
use Sibas\Http\Requests\Vd\ClientComplementFormRequest;
use Sibas\Repositories\Vd\DetailRepository;
use Sibas\Repositories\Vd\HeaderRepository;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\WsRepository;
use Sibas\Repositories\Vd\ResponseRepository;

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
     * @var ResposeRepository
     */
    private $responseRepository;

    /**
     * @var WsRepository
     */
    private $wsRepository;

    public function __construct(
            DetailRepository $repository,
            RetailerProductRepository $retailerProductRepository,
            HeaderRepository $headerRepository,
            ClientRepository $clientRepository,
            ResponseRepository $responseRepository,
            WsRepository $wsRepository
    )
    {
        $this->repository = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->headerRepository = $headerRepository;
        $this->clientRepository = $clientRepository;
        $this->responseRepository = $responseRepository;
        $this->wsRepository = $wsRepository;
    }

    use DataTrait;

    public function create($rp_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct    = $this->retailerProductRepository->getModel();
            $data               = $this->getData($rp_id);
            // dd($data["plans"]);
            $client             = new Client();

            if (session('client'))
                $client = session('client');
            // dd(decode($rp_id), $data, $client, $retailerProduct);
            return view($retailerProduct->companyProduct->product->code.'.create', compact('rp_id', 'data', 'client', 'retailerProduct'));
        }
        return redirect()->back();
    }

    public function store(ClientCreateFormRequest $request, $rp_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $parameter       = $retailerProduct->parameters->filter(function ($item) {
                return $item->slug === 'GE';
            })->first();
            $result = $this->headerRepository->storeHeader($request);
            if ($result) {
                $request['header'] = $this->headerRepository->getModel();
                $result = $parameter instanceof ProductParameter;
                if ($result) {
                    if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                        $this->clientRepository->getClientByDni($request->get('dni'), $request->get('extension'));
                        $client = $this->clientRepository->getModel();
                        
                        if($client->count() > 0)
                            if ($client->detailsVd)
                                $key = $this->repository->checkQuantityProducts($client->detailsVd, $retailerProduct->companyProduct->product->code, $request->get('dni'), $request->get('extension'), $client->detailsVd->first()->header->plan->name);
                            else
                                $key = true;
                        else
                            $key = true;
                        
                        if ($key) {
                            if ($this->clientRepository->createClient($request)) {
                                $request['client'] = $this->clientRepository->getModel();
                                $id_referred = '';

                                if ($this->repository->createDetail($request, $id_referred)) {
                                    $detail = $this->repository->getModel();

                                    return redirect()->route('de.question.create', [
                                        'rp_id' => $rp_id,
                                        'header_id' => encode($request['header']->id),
                                        'detail_id' => encode($detail->id),
                                    ])->with(['success_client' => 'La información del Cliente fue registrada']);
                                }
                            }
                        }else{
                            return redirect()->back()->with([
                                'error_client' => 'El cliente sobrepasa el limite de seguros de este producto',
                            ])->withInput()->withErrors($this->repository->getErrors());
                        }
                    } else {
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
                            if ($this->repository->editDetail($request)) {
                                ClientUpdate:
                                return redirect()->route($retailerProduct->companyProduct->product->code.'.client.list', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id
                                ])->with([ 'success_client' => 'La información del Cliente se actualizó correctamente' ]);
                            }
                        }
                    } else {
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

    public function storeTomador(Request $request, $rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))) {
            $retailerProduct    = $this->retailerProductRepository->getModel();
            $header             = $this->headerRepository->getModel();
            $client             = false;
            
            if($retailerProduct->ws){
                #WS buscar cliente

                if (request()->get('s_dni')) {
                    if (request()->get('s_extension') == 'PE')
                        $document_dni = 'E-'.request()->get('s_dni');
                    elseif (request()->get('s_extension') == 'NT')
                        $document_dni = request()->get('s_dni');
                    else
                        $document_dni = request()->get('s_dni').request()->get('s_extension');

                    if ($this->wsRepository->BuscarCliente($document_dni)) {
                        $prev_data = $this->wsRepository->getData();
                        //$prev_data = getBuscarCliente();
                        if($prev_data == '')
                            return redirect()->back()->with([ 'err_client' => 'No existe datos' ]);
                        #WS datos cliente
                        if (count($prev_data['NewDataSet']['cliente']) == 2)
                            $codigo_cliente = $prev_data['NewDataSet']['cliente'][1]['codigo'];
                        else
                            $codigo_cliente = $prev_data['NewDataSet']['cliente']['codigo'];
                            
                        if($this->wsRepository->DatosCliente($codigo_cliente)){
                            $data = $this->wsRepository->getData();
                            //$data = getDatosCliente();
                            if($data == '')
                                return redirect()->back()->with([ 'err_client' => 'Error Datos Cliente: No existe datos' ]);

                            $data = $data['NewDataSet']['cliente'];

                            if (request()->get('s_extension') == 'NT')
                                $request['business_name'] = trim($data['nombrecompleto']);

                            $request['code']                   = trim($data['codigo']);
                            $request['first_name']             = trim($data['nombres']);
                            $request['last_name']              = trim($data['apellidopaterno']);
                            $request['mother_last_name']       = trim($data['apellidomaterno']);
                            $request['dni']                    = request()->get('s_dni');
                            $request['extension']              = request()->get('s_extension');
                            $request['birthdate']              = date('d/m/Y',strtotime(trim($data['nacimiento'])));
                            $request['gender']                 = trim($data['genero']);
                            $request['home_address']           = trim($data['direccion']);
                            $request['business_address']       = trim($data['direcciontrabajo']);
                            $request['phone_number_home']      = trim($data['telefono']);
                            $request['phone_number_mobile']    = trim($data['celular']);
                            $request['email']                  = trim($data['email']);
                            $request['occupation_description'] = trim($data['ocupacion']);
                            $request['workplace']              = trim($data['profesion']);
                            $request['type']                   = 'N';

                            if($this->clientRepository->createClient($request))
                                $client = $this->clientRepository->getModel();

                        }else
                            return redirect()->back()->with([ 'err_client' => 'Error metodo DatosCliente: '.$this->wsRepository->getMessage() ]);
                    }else
                        return redirect()->back()->with([ 'err_client' => 'Error metodo Buscar cliente: '.$this->wsRepository->getMessage() ]);
                } else
                    return redirect()->back()->with(['err_client' => 'Error debe ingresar el CI y Extension']);
            }else{
                if($this->clientRepository->getClientByDni($request->get('s_dni')))
                    $client = $this->clientRepository->getModel();
            }
            
            $request['header']=$header;
            $request['client']=$client;
            $request['reference']='';
            if($client && $header->details->first()->client->id != $client->id){
                if($this->repository->getDetailById($header->details->first()->id) && $this->repository->updateHolder(false, 'A')){
                    $titular = $this->repository->getModel();
                    
                    $request['account_number']          = $titular->account_number;
                    $request['card_number']             = $titular->card_number;
                    $request['expiration_card_month']   = $titular->expiration_card_month;
                    $request['expiration_card_year']    = $titular->expiration_card_year;
                    
                    $this->repository->deleteHolder($header->id,strtolower($header->prefix));
                    
                    if($this->repository->createDetail($request, '') && $this->repository->updateHolder(true, 'T') && $this->repository->updateAccount($request))
                        return redirect()->back()->with([ 'success_header' => 'tomador actualizado con exito' ]);
                }
            }else
                return redirect()->back()->with([ 'error_detail' => 'Favor de seleccionar otro tomador' ])->withInput()
            ->withErrors($this->repository->getErrors());
        }

        return redirect()->back()->with([ 'error_detail' => 'El Cliente no pudo ser registrado' ])->withInput()
            ->withErrors($this->repository->getErrors());
    }
}