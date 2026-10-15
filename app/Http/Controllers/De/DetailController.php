<?php

namespace Sibas\Http\Controllers\De;

use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Sibas\Entities\Client;
use Sibas\Entities\De\Detail;
use Sibas\Entities\De\Header;
use Sibas\Entities\ProductParameter;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Client\ClientComplementFormRequest;
use Sibas\Http\Requests\Client\ClientCreateFormRequest;
use Sibas\Http\Requests\De\BalanceFormRequest;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\De\DetailRepository;
use Sibas\Repositories\De\FacultativeRepository;
use Sibas\Repositories\De\HeaderRepository;
use Sibas\Repositories\De\RejectedCustomerRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\StorageRepository;
use Sibas\Repositories\WsRepository;

class DetailController extends Controller
{

    /**
     * @var DetailRepository
     */
    protected $repository;

    /**
     * @var HeaderRepository
     */
    protected $headerRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var FacultativeRepository
     */
    protected $facultativeRepository;

    protected $reference;
    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;
    /**
     * @var StorageRepository
     */
    protected $storageRepository;
    /**
     * @var RejectedCustomerRepository
     */
    protected $rejectedCustomerRepository;
    /**
     * @var WsRepository
     */
    protected $ws;


    public function __construct(
        DetailRepository $repository,
        HeaderRepository $headerRepository,
        ClientRepository $clientRepository,
        FacultativeRepository $facultativeRepository,
        RetailerProductRepository $retailerProductRepository,
        StorageRepository $storageRepository,
        RejectedCustomerRepository $rejectedCustomerRepository,
        WsRepository $ws
    ) {
        $this->repository                = $repository;
        $this->headerRepository          = $headerRepository;
        $this->clientRepository          = $clientRepository;
        $this->facultativeRepository     = $facultativeRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->storageRepository         = $storageRepository;
        $this->rejectedCustomerRepository = $rejectedCustomerRepository;
        $this->ws                        = $ws;

        $this->reference = [ 'ISE', 'ISU' ];
    }


    use DataTrait;


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }


    /**
     * Show the form for creating a new Client.
     *
     * @param string $rp_id
     * @param string $header_id
     * @param null   $client_id
     *
     * @return Response
     */
    public function create($rp_id, $header_id, $client_id = null)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $header = $this->headerRepository->getModel();

                ClientCreate:
                $data   = $this->getData($rp_id);
                
                unset($data['type_of_contracts'][1]);
                $client = new Client();
                $detail = new Detail();

                if (session('client') && session('client') instanceof Client) {
                    $client = session('client');
                    $detail->detail_operations = $client->detail_operations;
                } elseif ( ! is_null($client_id) && $this->clientRepository->getClientById(decode($client_id))) {
                    $client = $this->clientRepository->getModel();
                }

                return view('client.de.create', compact('rp_id', 'header_id', 'header', 'detail', 'data', 'client'));
            } elseif (request()->session()->has('guest')) {
                /*
                 * Local Storage
                 */
                $this->storageRepository->setId(decode($header_id));

                if ($this->storageRepository->getDataStorage('guest')) {
                    $data   = $this->storageRepository->getData();
                    $header = new Header($data['header']);

                    goto ClientCreate;
                }
                /*
                 * Local Storage
                 */
            }
        }

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ClientCreateFormRequest $request
     * @param                          $rp_id
     * @param                          $header_id
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function store(ClientCreateFormRequest $request, $rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $parameter       = $retailerProduct->parameters->filter(function ($item) {
                return $item->slug === 'GE';
            })->first();

            /*
             * Verificar cobertura de desempleo
             */
            $iu_value = filter_var($request->get('involuntary_unemployment'), FILTER_VALIDATE_BOOLEAN);

            if ($iu_value) {
                $p_iu = $retailerProduct->parameters->filter(function ($item) {
                    return $item->slug === 'IU';
                })->first();

                if ($p_iu && ! $this->repository->checkAgeByParameter($request->get('birthdate'), $p_iu)) {
                    return redirect()->back()
                        ->with([
                            'error_client' => 'El Cliente no cumple con el rango de edades para la Cobertura de Desempleo. [ '
                                . $p_iu->age_min . ' - '
                                . $p_iu->age_max . ' ]',
                        ])->withInput()->withErrors($this->repository->getErrors());
                }
            }

            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $request['header'] = $this->headerRepository->getModel();

                if ($parameter instanceof ProductParameter) {
                    if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                        if ($this->clientRepository->createClient($request)) {
                            $request['client'] = $this->clientRepository->getModel();

                            if ($this->repository->createDetail($request)) {
                                $detail = $this->repository->getModel();

                                ClientSuccess:
                                return redirect()->route('de.question.create', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id,
                                    'detail_id' => encode($detail->id),
                                ])->with([ 'success_client' => 'La información del Cliente fue registrada' ]);
                            }
                        }
                    } else {
                        #registro de clientes rechazados por la edad
                       if($this->rejectedCustomerRepository->getRejectedCustomerByDni($request['header']->id, $request->get('dni')) == false) {
                           if($this->rejectedCustomerRepository->createRejectedCustomer($request))
                                $rejectedCustomer = $this->rejectedCustomerRepository->getModel();
                       }else
                            $this->rejectedCustomerRepository->updateRejectedCustomer($request);
                        
                        ClientAgeErr:
                        return redirect()->back()->with([
                            'error_client' => 'El Cliente no cumple con el rango de edades [ '
                                              . $parameter->age_min . ' - '
                                              . $parameter->age_max . ' ]',
                        ])->withInput()->withErrors($this->repository->getErrors());
                    }
                }
            }  elseif (request()->session()->has('guest')) {
                /*
                 * Local Storage
                 */
                $this->storageRepository->setId(decode($header_id));

                if ($this->storageRepository->getDataStorage('guest')) {
                    $data   = $this->storageRepository->getData();
                    $header = new Header($data['header']);

                    if ($parameter instanceof ProductParameter) {
                        if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                            $d              = $request->all();
                            $d['birthdate'] = $this->repository->carbon->createFromFormat('d/m/Y',
                                $d['birthdate'])->toDateString();

                            unset($d['_token'], $d['coverage'], $d['credit_product']);

                            $exchangeRate = $retailerProduct->retailer->exchangeRate;

                            $detail = [
                                'id'                => filter_var(date('U'), FILTER_VALIDATE_INT),
                                'amount'            => $this->repository->getAmountInBs($header->currency,
                                    $header->amount_requested, $exchangeRate->bs_value),
                                'approved'          => false,
                                'headline'          => '',
                                'percentage_credit' => '',
                                'client'            => $d,
                            ];

                            if ($header->coverage->slug === 'CO' && $request->has('percentage_credit')) {
                                $detail['percentage_credit'] = $request->get('percentage_credit');
                            } else {
                                $detail['percentage_credit'] = $this->repository->getPercentage($header);
                            }

                            if (! key_exists('details', $data['header'])) {
                                $data['header']['details'] = [];
                            }

                            array_push($data['header']['details'], $detail);

                            $this->storageRepository->storeDataStorage($data, 'guest');

                            $detail = new Detail($detail);

                            goto ClientSuccess;
                        } else {
                            goto ClientAgeErr;
                        }
                    }
                }
                /*
                 * Local Storage
                 */
            }
        }

        return redirect()->back()->with([ 'error_detail' => 'El Cliente no pudo ser registrado' ])->withInput()
            ->withErrors($this->repository->getErrors());
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param $rp_id
     * @param $header_id
     * @param $detail_id
     *
     * @return Response
     */
    public function edit($rp_id, $header_id, $detail_id)
    {
        if ($this->headerRepository->getHeaderById(decode($header_id))
            && $this->repository->getDetailById(decode($detail_id))) {
            $header = $this->headerRepository->getModel();
            $detail = $this->repository->getModel();

            if ($detail->client instanceof Client) {
                $client = $detail->client;

                ClientEdit:
                $data   = $this->getData($rp_id);
                unset($data['type_of_contracts'][1]);
                
                return view('client.de.edit',
                    compact('rp_id', 'header_id', 'detail_id', 'header', 'detail', 'data', 'client'));
            }
        } elseif (request()->session()->has('guest')) {
            /*
             * Local Storage
             */
            $this->storageRepository->setId(decode($header_id));

            if ($this->storageRepository->getDataStorage('guest')) {
                $data   = $this->storageRepository->getData();
                $header = new Header($data[ 'header' ]);

                foreach ($data['header']['details'] as $dt) {
                    if ($dt['id'] === decode($detail_id)) {
                        $detail         = new Detail($dt);
                        $detail->client = new Client($dt['client']);
                        $client         = $detail->client;
                        $detail->header = new Header($data['header']);

                        goto ClientEdit;
                    }
                }
            }
            /*
             * Local Storage
             */
        }

        return redirect()->back()->with([ 'error_client_edit' => 'El Cliente no existe' ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ClientCreateFormRequest $request
     * @param                          $rp_id
     * @param                          $header_id
     * @param                          $detail_id
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function update(ClientCreateFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $parameter       = $retailerProduct->parameters->filter(function ($item) {
                return $item->slug === 'GE';
            })->first();

            /*
             * Verificar cobertura de desempleo
             */
            $iu_value = filter_var($request->get('involuntary_unemployment'), FILTER_VALIDATE_BOOLEAN);

            if ($iu_value) {
                $p_iu = $retailerProduct->parameters->filter(function ($item) {
                    return $item->slug === 'IU';
                })->first();

                if ($p_iu && ! $this->repository->checkAgeByParameter($request->get('birthdate'), $p_iu)) {
                    return redirect()->back()
                        ->with([
                            'error_client' => 'El Cliente no cumple con el rango de edades para la Cobertura de Desempleo. [ '
                                . $p_iu->age_min . ' - '
                                . $p_iu->age_max . ' ]',
                        ])->withInput()->withErrors($this->repository->getErrors());
                }
            }

            if ($this->headerRepository->getHeaderById(decode($header_id))
                && $this->repository->getDetailById(decode($detail_id))) {
                $request[ 'header' ] = $this->headerRepository->getModel();
                $detail              = $this->repository->getModel();

                if ($parameter instanceof ProductParameter) {
                    if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                        if ($this->clientRepository->editClient($request, $detail->client)) {
                            if ($this->repository->editDetail($request)) {
                                ClientUpdate:
                                return redirect()->route('de.client.list', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id
                                ])->with([ 'success_client' => 'La información del Cliente se actualizó correctamente' ]);
                            }
                        }
                    } else {
                        #registro de clientes rechazados por la edad
                        if($this->rejectedCustomerRepository->getRejectedCustomerByDni($request['header']->id, $request->get('dni')) == false) {
                            if($this->rejectedCustomerRepository->createRejectedCustomer($request))
                                 $rejectedCustomer = $this->rejectedCustomerRepository->getModel();
                        }else
                        $this->rejectedCustomerRepository->updateRejectedCustomer($request);
                        
                        ClientAgeErr:
                        return redirect()->back()->with([
                            'error_client' => 'El Cliente no cumple con el rango de edades [ '
                                              . $parameter->age_min . ' - '
                                              . $parameter->age_max . ' ]',
                        ])->withInput()->withErrors($this->repository->getErrors());
                    }
                }
            } elseif (request()->session()->has('guest')) {
                /*
                 * Local Storage
                 */
                $this->storageRepository->setId(decode($header_id));

                if ($this->storageRepository->getDataStorage('guest')) {
                    $data   = $this->storageRepository->getData();
                    $header = new Header($data[ 'header' ]);

                    if ($parameter instanceof ProductParameter) {
                        if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                            $d                = $request->all();
                            $d[ 'birthdate' ] = $this->repository->carbon->createFromFormat('d/m/Y', $d[ 'birthdate' ])
                                ->toDateString();

                            unset($d['_token'], $d['coverage'], $d['credit_product'], $d['_method'], $d['percentage_credit']);

                            foreach ($data['header']['details'] as &$detail) {
                                if ($detail['id'] === decode($detail_id)) {
                                    if ($header->coverage->slug === 'CO' && $request->has('percentage_credit')) {
                                        $detail['percentage_credit'] = $request->get('percentage_credit');
                                    } else {
                                        $detail['percentage_credit'] = $this->repository->getPercentage($header);
                                    }

                                    $detail['client'] = $d;

                                    $this->storageRepository->storeDataStorage($data, 'guest');

                                    goto ClientUpdate;
                                }
                            }
                        } else {
                            goto ClientAgeErr;
                        }
                    }
                }
                /*
                 * Local Storage
                 */
            }
        }

        return redirect()->back()
            ->with([ 'error_client_edit' => 'La información del Cliente no puede ser actualizada' ])->withInput()
            ->withErrors($this->repository->getErrors());
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     * @param string $detail_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateClient($rp_id, $header_id, $detail_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->headerRepository->getHeaderById(decode($header_id))) {
            /**
             * @var Detail $detail
             */
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->headerRepository->getModel();
            $ws              = $retailerProduct->ws;
            $validated       = false;
            $detail          = $header->details->where('id', decode($detail_id))->first();

            if ($detail instanceof Detail) {
                $dni        = $detail->client->dni;
                $complement = $detail->client->complement;
                $client     = null;

                if ($ws && $this->ws->getClient($dni, $complement)) {
                    $data   = $this->ws->getData();
                    $status = $this->ws->getStatus();

                    if ($status !== 500) {
                        $client     = [];
                        $addresses  = $this->clientRepository->getAddresses($data);

                        $client['last_name']              = $data['ApellidoPaterno'];
                        $client['mother_last_name']       = $data['ApellidoMaterno'];
                        $client['first_name']             = $data['Nombres'];
                        $client['birthdate']              = $this->repository->carbon->createFromFormat('d/m/Y', $data['FecNac'])->toDateString();
                        $client['dni']                    = $data['DocId'];
                        $client['complement']             = trim($data['Complemento']);
                        $client['home_address']           = $addresses[0];
                        $client['business_address']       = $addresses[1];
                        $client['occupation_description'] = $data['Profesion'] . ( ! empty($data['Ocupacion']) ? ' - ' . trim($data['Ocupacion']) : '' );
                        $client['debit_balance']          = empty($data['SaldoInsoluto']) ? 0 : $data['SaldoInsoluto'];
                        $client['addresses']              = json_encode($addresses);
                        $client['operations']             = $data['Operaciones'];
                    }
                } elseif (! $ws && $this->clientRepository->getClientByDni($dni, $complement, true)) {
                    $client = $this->clientRepository->getModel()->toArray();
                }

                if (is_array($client)) {
                    try {
                        $detail->update([
                            'validated' => true,
                        ]);

                        $detail->client()->update($client);

                        $validated = true;
                    } catch (QueryException $e) {
                        $errors = $e->getMessage();
                    }

                    if ($validated) {
                        return redirect()->route('de.detail.i.edit', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                            'detail_id' => $detail_id,
                            'ref'       => 'ise',
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with(['validated' => 'No existen resultados.']);
    }


    /**
     * Show the form for add complementary data.
     *
     * @param      $rp_id
     * @param      $header_id
     * @param      $detail_id
     * @param null $ref
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editIssue($rp_id, $header_id, $detail_id, $ref = null)
    {
        if ($this->headerRepository->getHeaderById(decode($header_id))) {
            $header = $this->headerRepository->getModel();
            $ref    = strtoupper($ref);

            if ($this->repository->getDetailById(decode($detail_id))) {
                $detail = $this->repository->getModel();

                if (in_array($ref, $this->reference)) {
                    $data   = $this->getData($rp_id);
                    $client = $detail->client;

                    if ($client instanceof Client) {
                        if ($ref === 'ISE') {
                            return view('client.de.detail-edit',
                                compact('rp_id', 'header_id', 'ref', 'header', 'data', 'detail'));
                        } elseif (strtoupper($ref) === 'ISU') {
                            // return view('client.de.edit', compact('rp_id', 'header_id', 'data', 'client'));
                        }
                    }
                }
            }
        }

        return redirect()->back()->with([ 'error_client' => 'La información del Cliente no puede ser editada' ]);
    }


    public function updateIssue(ClientComplementFormRequest $request, $rp_id, $header_id, $detail_id, $ref)
    {
        if ($this->headerRepository->getHeaderById(decode($header_id))
            && $this->repository->getDetailById(decode($detail_id))
        ) {
            $request['header'] = $this->headerRepository->getModel();
            $detail            = $this->repository->getModel();
            $ref               = strtoupper($ref);

            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $parameter       = $retailerProduct->parameters->filter(function ($item) {
                    return $item->slug === 'GE';
                })->first();

                if ($parameter instanceof ProductParameter) {
                    if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                        if (in_array($ref, $this->reference)) {
                            if (( $detail->client instanceof Client )
                                && $this->clientRepository->updateIssueClient($request, $detail->client)
                            ) {
                                if ($this->repository->editDetail($request)) {
                                    return redirect()->route('de.edit', [
                                        'rp_id'     => $rp_id,
                                        'header_id' => $header_id,
                                        $request->has('_idf') ? 'idf=' . e($request->get('_idf')) : null
                                    ])->with([ 'success_client' => 'La información del Cliente se actualizó correctamente' ]);
                                }
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

        return redirect()->back()->with([ 'error_client' => 'La información del Cliente no pudo ser actualizada' ])->withInput()->withErrors($this->repository->getErrors());
    }


    public function editBalance($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $header   = $this->headerRepository->getModel();
                $detail   = $header->details()->where('id', decode($detail_id))->first();
                $retailer = request()->user()->retailerUser->retailer;

                if ($detail instanceof Detail) {
                    $payload             = view('client.de.balance', compact('rp_id', 'header', 'detail', 'retailer'));
                    $amount_requested_bs = $header->currency == 'USD' ? $header->amount_requested * $retailer->exchangeRate->bs_value : $header->amount_requested;
                        
                    //$balance = ($detail->cumulus > 0)? $detail->balance : $detail->client->debit_balance;
                    $debit_balance = $detail->client->debit_balance;
                    
                    if ($header->creditProduct->slug == 'PCA'){
                        if (($amount_requested_bs / $retailer->exchangeRate->bs_value) <= 20000)
                            $debit_balance = 0;
                    }
                    
                    return response()->json([
                        'payload'             => $payload->render(),
                        'amount_requested'    => $header->amount_requested,
                        //'amount_requested_bs' => ($header->creditProduct->slug=='PLT' || $header->creditProduct->slug=='PLNT')?0:$amount_requested_bs,
                        'amount_requested_bs' => $amount_requested_bs,
                        //'balance'             => $balance,
                        'balance'             => ( $detail->cumulus > 0 ? $detail->balance : $debit_balance ),
                        'cumulus'             => $detail->cumulus,
                        'refinancing'         => $detail->refinancing,
                        'parallel_credit'     => $detail->parallel_credit,
                        'movement_type'       => $header->movement_type,
                        'credit_product'       => $header->creditProduct->slug,
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back()->with([ 'error_detail' => 'El Saldo Deudor no puede ser editado' ]);

    }


    public function updateBalance(BalanceFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        // dd("Resultado");
        if ($request->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $request['header'] = $this->headerRepository->getModel();

                if ($this->repository->updateBalance($request, decode($detail_id))) {
                    $request['detail']   = $this->repository->getModel();
                    $request['retailer'] = $request->user()->retailerUser->retailer;

                    $approved = true;
                    if (! $request['header']->credit_line) {
                        if ($this->facultativeRepository->storeFacultative($request, decode($rp_id))) {
                            $approved = false;
                        }
                    }

                    $header = $this->repository->getModel()->header;

                    $facultative = false;

                    if ($header->type === 'I') {
                        $facultative = $this->headerRepository->setFacultative($header);
                    }

                    $this->repository->setApprovedDetail($approved, $facultative);

                    return response()->json([
                        'location' => route('de.edit', compact('rp_id', 'header_id'))
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
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
     * @return Response
     */
    public function destroy($rp_id, $header_id, $detail_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getDetailById(decode($detail_id)) && $this->repository->removeDetail()) {
                return response()->json([
                    'location' => route('de.client.list', [ 'rp_id' => $rp_id, 'header_id' => $header_id ])
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

}
