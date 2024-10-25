<?php

namespace Sibas\Http\Controllers\De;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Monolog\Logger;
use Sibas\Entities\Client;
use Sibas\Entities\De\Detail;
use Sibas\Entities\De\Facultative;
use Sibas\Entities\De\Header;
use Sibas\Entities\Rate;
use Sibas\Entities\RetailerProduct;
use Sibas\Http\Controllers\CertificateController;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Controllers\MailController;
use Sibas\Http\Controllers\PdfController;
use Sibas\Http\Requests\De\FacultativeRequestFormRequest;
use Sibas\Http\Requests\De\HeaderCreateFormRequest;
use Sibas\Http\Requests\De\HeaderEditFormRequest;
use Sibas\Repositories\Adm\SettingAdmRepository;
use Sibas\Repositories\CertificateRepository;
use Sibas\Repositories\De\CreditLineRepository;
use Sibas\Repositories\De\DataRepository;
use Sibas\Repositories\De\DetailRepository;
use Sibas\Repositories\De\FacultativeRepository;
use Sibas\Repositories\De\HeaderRepository;
use Sibas\Repositories\LogRepository;
use Sibas\Repositories\Retailer\CityRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\StorageRepository;
use Sibas\Repositories\WsRepository;

class HeaderController extends Controller
{

    /**
     * @var HeaderRepository
     */
    protected $repository;

    /**
     * @var DataRepository
     */
    protected $dataRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var PolicyRepository
     */
    private $policyRepository;

    /**
     * @var Rate
     */
    protected $rate;

    /**
     * @var FacultativeRepository
     */
    protected $facultativeRepository;
    /**
     * @var LogRepository
     */
    protected $logRepository;
    /**
     * @var StorageRepository
     */
    protected $storageRepository;
    /**
     * @var WsRepository
     */
    protected $wsRepository;
    /**
     * @var SettingAdmRepository
     */
    protected $settingAdmRepository;
    /**
     * @var CreditLineRepository
     */
    protected $creditLineRepository;
    /**
     * @var CityRepository
     */
    protected $cityRepository;
    /**
     * @var DetailRepository
     */
    protected $detailRepository;


    public function __construct(
        HeaderRepository $repository,
        DetailRepository $detailRepository,
        DataRepository $dataRepository,
        RetailerProductRepository $retailerProductRepository,
        PolicyRepository $policyRepository,
        FacultativeRepository $facultativeRepository,
        LogRepository $logRepository,
        StorageRepository $storageRepository,
        WsRepository $wsRepository,
        SettingAdmRepository $settingAdmRepository,
        CreditLineRepository $creditLineRepository,
        CityRepository $cityRepository
    ) {
        $this->repository                = $repository;
        $this->detailRepository          = $detailRepository;
        $this->dataRepository            = $dataRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->policyRepository          = $policyRepository;
        $this->facultativeRepository     = $facultativeRepository;
        $this->logRepository             = $logRepository;
        $this->storageRepository         = $storageRepository;
        $this->wsRepository              = $wsRepository;
        $this->settingAdmRepository      = $settingAdmRepository;
        $this->creditLineRepository      = $creditLineRepository;
        $this->cityRepository            = $cityRepository;

        $this->rate = null;
    }


    /**
     * Returns data for create Header
     *
     * @param string  $rp_id
     * @param boolean $credit_line
     *
     * @return array
     */
    protected function getData($rp_id, $credit_line = false)
    {
        return [
            'coverages'       => $this->retailerProductRepository->getCoverageByProduct($rp_id),
            'currencies'      => $this->dataRepository->getCurrency(),
            'term_types'      => $this->dataRepository->getTermType(),
            'credit_products' => $this->retailerProductRepository->getCreditProductByProduct($rp_id, $credit_line),
            'movement_types'  => $this->dataRepository->getMovementType(),
            'policies'        => $this->policyRepository->getPolicyForIssuance($rp_id),
            'types_of_requests' => $this->dataRepository->getTypesOfRequests(),
        ];
    }


    /**
     * Display a listing of the Clients.
     *
     * @param string $rp_id
     * @param string $header_id
     *
     * @return Response
     */
    public function lists($rp_id, $header_id)
    {
        $header = null;

        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            if (request()->session()->has('guest')) {
                /*
                 * Local Storage
                 */
                $this->storageRepository->setId(decode($header_id));

                if ($this->storageRepository->getDataStorage('guest')) {
                    $data            = $this->storageRepository->getData();
                    $header          = new Header($data['header']);
                    $header->details = collect([]);

                    if (key_exists('details', $data['header'])) {
                        foreach ($data['header']['details'] as $dt) {
                            $detail         = new Detail($dt);
                            $detail->client = new Client($dt['client']);

                            $header->details->push($detail);
                        }
                    }

                    goto HeaderList;
                }
                /*
                 * Local Storage
                 */
            }

            if ($this->repository->getHeaderById(decode($header_id))) {
                $header = $this->repository->getModel();

                HeaderList:
                $user            = auth()->user();
                $retailerProduct = $this->retailerProductRepository->getModel();
                $coverage_detail = 0;

                foreach ($retailerProduct->coverages as $coverage) {
                    if ($coverage->id === $header->ad_coverage_id) {
                        $coverage_detail = $coverage->pivot->detail;
                    }
                }
            }

            return view('de.list',
                compact('rp_id', 'header_id', 'header', 'coverage_detail', 'user', 'retailerProduct'));
        }

        return redirect()->back();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param string $rp_id
     *
     * @return Response
     */
    public function create($rp_id)
    {
        $credit_line = is_null(request()->query('cl')) ? false : true;

        $data = $this->getData(decode($rp_id), $credit_line);

        return view('de.create', compact('rp_id', 'data', 'credit_line'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param HeaderCreateFormRequest $request
     * @param string                  $rp_id
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function store(HeaderCreateFormRequest $request, $rp_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();

            if ($request->has('credit_line')) {
                $credit_product = $request->get('credit_product');

                $cl = $this->creditLineRepository->checkCreditLineNumber($request->get('number_line'), $credit_product);

                if ($cl === 1) {
                    if ($request->has('operation_number')) {
                        $credit_line      = $this->creditLineRepository->getModel();
                        $operation_number = $request->get('operation_number');

                        /*
                         * WS Record Data
                         */
                        if ($retailerProduct->ws) {
                            $old = $this->repository->checkOldOperationNumber($operation_number);

                            if (! $this->wsRepository->setDeDataUnderLine($credit_line, $operation_number, $old)) {
                                $message = 'El Número de Operación no pudo ser registrado.';
                                $records = $this->wsRepository->getDataRecords();
                                $emails  = $this->repository->emailsForWs();

                                Mail::send('emails.de.ws-warning', ['credit_line' => $credit_line, 'records' => $records], function ($m) use ($credit_line, $emails) {
                                    $m->from(env('MAIL_USERNAME'), env('MAIL_NAME'));

                                    foreach ($emails as $email => $name) {
                                        $m->to($email, $name);
                                    }

                                    $m->subject('WS Error: Línea de Crédito No. ' . $credit_line->number_line);
                                });

                                if (count(Mail::failures()) > 0) {
                                    $message .= ' warning.';
                                }

                                return redirect()->back()->with([ 'error_header' => $message ])
                                                 ->withErrors($this->repository->getErrors());
                            }
                        }

                        return redirect()->back()
                                         ->with([ 'success_header' => 'El Número de Operación se registró correctamente.' ])
                                         ->withErrors($this->repository->getErrors());
                    }

                    return redirect()->back()->with([ 'success_cl' => true, ])
                                     ->withInput()
                                     ->withErrors($this->repository->getErrors());
                } elseif ($cl === 3) {
                    return redirect()->back()->with([ 'error_header' => 'La Línea Madre no cuenta con seguro.', ])
                                     ->withInput()
                                     ->withErrors($this->repository->getErrors());
                } elseif ($cl === 0) {
                    return redirect()->back()->with([ 'error_header' => 'El número de línea no existe.', ])
                                     ->withInput()
                                     ->withErrors($this->repository->getErrors());
                }
            }

            $type_term = $request->get('type_term');
            $terms     = [
                'Y' => 30,
                'M' => 360,
                'W' => 1560,
                'D' => 10980,
            ];

            if ($request->get('term') > $terms[$type_term]) {
                $message = 'El Plazo del Crédito no debe ser mayor a '
                           . $terms[ $type_term ]
                           . ' '
                           . config('base.term_types.' . $type_term);

                return redirect()->back()->with([ 'error_header' => $message, ])
                                         ->withInput()
                                         ->withErrors($this->repository->getErrors());
            }

            $header_id = '';

            /*
             * Local Storage
             */
            if ($request->session()->has('guest')) {
                $d     = $request->all();
                $guest = $request->session()->get('guest');

                $header_id                 = filter_var(date('U'), FILTER_VALIDATE_INT);
                $d['id']                   = $header_id;
                $d['ad_coverage_id']       = filter_var($d['coverage'], FILTER_VALIDATE_INT);
                $d['amount_requested']     = filter_var($d['amount_requested'], FILTER_VALIDATE_FLOAT);
                $d['ad_credit_product_id'] = filter_var($d['credit_product'], FILTER_VALIDATE_INT);
                $d['ad_guest_id']          = $guest->id;

                unset($d['coverage'], $d['_token'], $d['credit_product']);

                $data = [
                    'header'  => $d,
                    'user_id' => auth()->user()->id,
                ];

                $this->storageRepository->setId($header_id);
                $this->storageRepository->storeDataStorage($data, 'guest');

                goto HeaderList;
            }
            /*
             * Local Storage
             */

            if ($this->repository->createHeader($request)) {
                $header    = $this->repository->getModel();
                $header_id = $header->id;

                HeaderList:
                $message = 'La cotización fue registrada con éxito.';

                return redirect()->route('de.client.list', [
                    'rp_id'     => $rp_id,
                    'header_id' => encode($header_id),
                ])->with([ 'success_header' => $message ]);
            }
        }

        return redirect()->back()
                         ->with([ 'error_header' => 'La cotización no pudo ser registrada' ])
                         ->withInput()
                         ->withErrors($this->repository->getErrors());
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param string $rp_id
     * @param string $header_id
     *
     * @return Response
     */
    public function edit($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->repository->getModel();
            $process         = false;

            foreach ($header->details as $detail) {
                if ($detail->process) {
                    $process = true;
                }
            }

            if (! $process) {
                $cities = $this->cityRepository->getCitiesByType()['DE'];
                $cities = $cities->map(function ($item, $key) {
                    if (key_exists('city_id', $item)) {
                        $item['id'] = $item['city_id'];
                    }

                    return $item;
                });

                $data           = $this->getData(decode($rp_id));
                $data['cities'] = $cities;
                //$data['vg']     = $this->repository->getStatusVg($retailerProduct, $header);
                $data['vg']     = true;
               
                if($header->creditProduct->slug == 'PCA')
                    $type = 'TJ';
                else
                    $type = ( $data['vg'] && ! in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ]) ) ? 'VI' : 'DE';

                $policies = $data['policies']->filter(function ($value) use ($type) {
                    //if($type!='TJ' && $value->ad_city_id == request()->user()->ad_city_id)
                    if($type!='TJ' && $value->ad_city_id == 7)#santa cruz
                        return $value->type === $type;
                    else{
                        return $value->modality === $type;
                    }
                        
                });
                
                $data['policies'] = $policies;

                $cumulus = $header->details->sum(function ($detail) {
                    return $detail->cumulus;
                });

                $header->cumulus = $cumulus;
                $client          = $header->details->filter(function ($item) {
                    return $item->headline === 'D';
                })->first()->client;
                
                $pca_validate = $this->getTargetaValidate($header, $retailerProduct);
                
                return view('de.edit', compact('rp_id', 'header_id', 'header', 'client', 'data', 'pca_validate'));
            } else {
                $dni = request()->session()->get('process_dni');
                $err = 'No se puede registrar afiliaciones para el prestatario con número de C.I. [ '
                       . implode(', ', $dni) . ' ] ya que actualmente tiene otros procesos en evaluación.';
               
                return redirect()->back()->with([ 'error_header' => $err ]);
            }
        }

        return redirect()->back();
    }

    public function getTargetaValidate($header, $retailerProduct)
    {
        if ($header->creditProduct->slug == 'PCA') {
            if ($header->currency == 'USD')
                $value = $header->amount_requested;
            else
                $value = $header->amount_requested/$retailerProduct->retailer->exchangeRate->bs_value;

            return ($value > 20000)?false : true;
        }
        return true;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HeaderEditFormRequest $request
     * @param string                $rp_id
     * @param string                $header_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(HeaderEditFormRequest $request, $rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            
            if($request->get('credit_product') == 4)
                $check = $this->repository->checkCardNumber($request->get('card_number'));
            else
                $check = $this->repository->checkOldOperationNumber($request->get('operation_number'));
            
            if ($check) {
                if ($this->repository->getHeaderById(decode($header_id))) {
                    if ($this->repository->checkPercentageParticipation()) {
                        if ($this->repository->updateHeader($request)) {
                            return redirect()->route('de.edit', [
                                'rp_id'     => $rp_id,
                                'header_id' => $header_id,
                            ])->with([ 'success_header' => 'La Póliza fue actualizada con éxito.' ]);
                        }
                    } else {
                        return redirect()
                            ->back()
                            ->withInput()
                            ->with(['error_ws' => 'El total del Porcentaje de Participación debe ser igual al 100%']);
                    }
                }
            } else {
                $mesage = ($request->get('credit_product') == 4)?'Tarjeta':'Operación';
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(['error_ws' => 'Existe un certificado emitido con el mismo Número de '.$mesage.', favor proceda con la respectiva anulación si corresponde']);
            }
        }

        return redirect()->back()->withInput()->with([ 'error_header' => 'La Póliza no pudo ser actualizada.' ])
                                              ->withErrors($this->repository->getErrors());
    }


    /**
     * Show all options for Company
     *
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\View\View
     */
    public function result($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            
            if (auth()->user()->profile->first()->slug === 'GUP' && request()->session()->has('guest')) {
                /*
                 * Local Storage
                 */
                $this->storageRepository->setId(decode($header_id));

                if ($this->storageRepository->getDataStorage('guest')) {
                    $data = $this->storageRepository->getData();

                    $total = 0;
                    foreach ($data['header']['details'] as $detail) {
                        $total += $detail['percentage_credit'];
                    }

                    if ($total != 100) {
                        goto ErrPercentage;
                    }

                    if ($this->repository->headerStoreGuest($data, $header_id, $retailerProduct->id)) {
                        $this->storageRepository->deleteDataStorage('guest');

                        goto ShowResult;
                    }
                } else {
                    goto Result;
                }
                /*
                 * Local Storage
                 */
            }
            
            Result:
            if ($this->repository->getHeaderById(decode($header_id))) {
                ShowResult:
                $header    = $this->repository->getModel();
                $user      = request()->user();
                $responses = '';

                foreach ($header->details as $detail) {
                    if (is_null($detail->response)) {
                        $responses .= 'El Cuestionario de Salud del cliente '
                                    . $detail->client->full_name
                                    . ' no se registró correctamente. <br>';
                    }
                }
                
                if (! empty($responses)) {
                    return redirect()->back()->with(['responses' => $responses]);
                }

                if ($this->repository->checkPercentageParticipation()) {
                    if ($user->profile->first()->slug === 'GUP') {
                        return view('de.result-guest', compact('rp_id', 'header_id', 'header'));
                    }

                    $process = 0;
                    $dni     = [];

                    foreach ($header->details as $detail) {
                        $p = $this->detailRepository->checkDetailPending($detail->client->dni, $detail->client->extension);

                        if ($p > 0) {
                            $process += 1;
                            $dni[] = $detail->client->dni;

                            $detail->update([
                                'process' => true,
                            ]);
                        } else {
                            $detail->update([
                                'process' => false,
                            ]);
                        }
                    }

                    if ($process > 0) {
                        request()->session()->put('process_dni', $dni);
                    } else {
                        request()->session()->forget('process_dni');
                    }

                    # set rate cesantia
                    if($this->retailerProductRepository->getRetailerProductByCodeProduct('ce'))
                        $this->detailRepository->setRateCesantiaByRetarilerProduct($header->details, $this->retailerProductRepository->getModel());
                    
                    $retailerProduct = $this->parameterRate($header, $retailerProduct);

                    if ($this->repository->setHeaderResult($retailerProduct, $header)) {
                        return view('de.result', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
                    }
                } else {
                    ErrPercentage:
                    return redirect()->back()
                        ->with([ 'error_header' => 'El total del Porcentaje de Participación debe ser igual al 100%' ]);
                }
            }
        }

        return redirect()->back()->with([ 'error_header' => 'La tasa no pudo ser registrada' ]);
    }


    /**
     * @param Model|Header $header
     * @param Model|RetailerProduct $retailerProduct
     *
     * @return RetailerProduct
     */
    protected function parameterRate($header, $retailerProduct)
    {
        $rp = $retailerProduct->retailer->retailerProducts()
            ->with([ 'rates.creditProduct' ])
            ->whereHas('companyProduct.product', function ($q) {
                $q->where('code', 'de');
            });

        if (in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ])) {
            $rp->whereHas('creditProducts', function ($q) use ($header) {
                $q->where('slug', $header->creditProduct->slug);
            })->whereHas('rates.creditProduct', function ($q) use ($header) {
                $q->where('slug', $header->creditProduct->slug);
            })->whereIn('type', [ 'RP', 'MP' ]);
        } else {
            $rp->whereHas('creditProducts', function ($q) use ($header) {
                $q->whereNotIn('slug', [ 'PMO', 'PLT' ]);
            })->where([ 'type' => 'MP' ]);
        }

        return $rp->first();
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function issue($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->repository->getModel();
            $user            = auth()->user();

            DB::enableQueryLog();

            if ($header->facultative && in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ])) {
                $retailerProduct = $this->parameterRate($header, $retailerProduct);
                
                if (! $this->repository->setHeaderResult($retailerProduct, $header, true)) {
                    return redirect()->back()->with([ 'error_header' => 'La tasa no pudo ser registrada.' ]);
                }
            }

            if (! $header->credit_line && in_array($header->creditProduct->slug, [ 'PLT', 'PLNT' ])) {
                $id      = date('U');
                $clients = [];

                foreach ($header->details as $index => $detail) {
                    $id = $id - 1;

                    array_push($clients, [
                        'id'                => $id,
                        'dni'               => $detail->client->dni,
                        'extension'         => $detail->client->extension,
                        'complement'        => $detail->client->complement,
                        'number'            => $detail->client->number,
                        'numbers'           => [$detail->client->number],
                        'percentage_credit' => $detail->percentage_credit,
                        'rate'              => ($detail->rate * 12),
                        'percentage'        => (! is_null($detail->facultative) ? $detail->facultative->percentage : 0),
                        'terms'             => '',
                        'insured'           => true,
                        'headline'          => ($index + 1),
                    ]);
                }

                try {
                    $credit_line = $header->creditLine()->create([
                        'id'           => $id,
                        'ad_user_id'   => $user->id,
                        'number_line'  => $header->operation_number,
                        'coverage'     => ($header->coverage->slug === 'CO' ? 'MC' : $header->coverage->slug),
                        'policy_type'  => ($header->creditProduct->slug === 'PLT' ? 'TE' : ($header->creditProduct->slug === 'PCA' ? 'CA' : 'TN')),
                        'free_cover'   => false,
                        'confirmed'    => true,
                        'deleted'      => false,
                        'data_records' => [],
                    ]);

                    $credit_line->clients()->createMany($clients);
                } catch (QueryException $e) {
                    $mess = 'Ocurrió un error al intentar recuperar una Línea de Crédito asociada a la Operación. 
                                    Contacte a su administrador de sistema.';

                    return redirect()->back()->with([ 'error_ws' => $mess ]);
                }
            }
            //dd($retailerProduct->companyProduct->product->name, $header->details->first());
            
            if(in_array($header->creditProduct->slug, ['PCA', 'PLT', 'PLNT'])) {
                $data = [];
                goto issue_de;
            }
            
                // if($header->planchado_tasa_fortaleza==false)
                //     $data = $this->wsRepository->storeRates2($header->prefix, $this->repository->getParamDe($header,$retailerProduct));
                // else
                //     goto issue_de;
                $data['err'] = '';
                if($data['err'] == ''){
                    // $header->update(['planchado_tasa_fortaleza'=>true]);
                    issue_de:
                    
                    
                    // if($header->creditProduct->slug != 'PCA' && $this->planchadoTasaCesantia($header, $retailerProduct) == false)
                    //     return redirect()->back()->with('success_header', 'CESANTIA - '.$this->data_c);

                    if ($this->repository->issueHeader()) {
                        /*
                        * Log - issuance
                        */
                        $header  = $header->fresh(['details.client', 'user']);
                        $queries = DB::getQueryLog();
                        $extra   = [
                            'user'               => $user->username,
                            'certificate_number' => $header->issue_number,
                            'date_issuance'      => Carbon::now(),
                        ];

                        $this->logRepository->setLog('DE issuance', $queries, $user, Logger::INFO, $extra);

                        DB::disableQueryLog();

                        $messageWs = (count($data)>0)?$data['err']:'';
                        $mess = ' y el certificado fue enviado al Cliente. '.$messageWs;
                        /*
                        * Mail Certificate
                        */

                        return redirect()->route('de.issuance', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                        ])->with([ 'success_header' => 'La Poliza fue emitida con exito' . $mess ]);
                    }
                }else
                    return redirect()->back()->with('success_header', 'DESGRAVAMEN - '.$data['err']);
               
        } 
        return redirect()->back()->with('success_header', 'No se pudo emitir la póliza');
    }

    public function planchadoTasaCesantia($header, $retailerProduct)
    {
        $this->data_c = '';
        if(!in_array($header->creditProduct->slug, ['PCA', 'PLT', 'PLNT']) && $header->details()->where('cesantia',true)->count() > 0 && $header->details()->where('send_fortaleza',true)->count() == 0){
            
            $data_c = $this->wsRepository->storeRates2($header->prefix, $this->repository->getParamCesantia($header,$retailerProduct));
            
            if($data_c['err'] == ''){
                foreach($header->details as $key => $value){
                    if($value->cesantia==true)
                        $value->update(['send_fortaleza'=>true]);
                }
            }else{
                $this->data_c = $data_c['err'];
                return false;
            }
        }
        return true; 
    }

    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return mixed
     */
    public function issuance($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->repository->getHeaderById(decode($header_id))
        ) {
            $header          = $this->repository->getModel();
            $retailerProduct = $this->retailerProductRepository->getModel();
            $retailer        = $retailerProduct->retailer;
            $settings        = $this->settingAdmRepository->getSettingsByModel('host_files');

            if ($settings->count() > 0) {
                $settings = $settings->pluck('logs.0.value', 'codename');
                $settings['host_secure'] = filter_var($settings['host_secure'], FILTER_VALIDATE_BOOLEAN);

                return view('de.issuance',
                    compact('rp_id', 'header_id', 'header', 'retailerProduct', 'retailer', 'settings'));
            }
        }

        return redirect()->back();
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     * @param string $sp_id
     *
     * @return mixed
     */
    public function viSPList($rp_id, $header_id, $sp_id)
    {
        if ($this->repository->getHeaderById(decode($header_id))) {
            $header = $this->repository->getModel();

            return view('vi.sp.list', compact('rp_id', 'header_id', 'header', 'sp_id'));
        }

        return redirect()->back()->with([ 'error_header' => 'El numero de Poliza no existe' ]);
    }


    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function viSPListStore(Request $request)
    {
        if ($request->has('clients')) {
            $rp_id     = $request->get('rp_id');
            $header_id = $request->get('header_id');
            $sp_id     = $request->get('sp_id');
            $clients   = $request->get('clients');

            $this->repository->setClientCacheSP($header_id, $clients);

            return redirect()->route('de.vi.sp.create', [
                'rp_id'     => decrypt($rp_id),
                'header_id' => $header_id,
                'sp_id'     => $sp_id,
            ]);
        }

        return redirect()->back()->with([ 'error_cache' => 'El Sub-Producto no puede ser procesado' ]);
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function requestCreate($rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getHeaderById(decode($header_id))) {
                $header = $this->repository->getModel();

                $observation = '';

                foreach ($header->details as $detail) {
                    if ( ! $detail->approved && ( $detail->facultative instanceof Facultative )) {
                        $observation .= $detail->facultative->reason;
                    }
                }

                if ( ! empty( $observation )) {
                    $header->facultative_observation = $observation;

                    $payload = view('de.facultative.request', compact('rp_id', 'header'));

                    return response()->json([
                        'payload'                 => $payload->render(),
                        'facultative_observation' => strip_tags($header->facultative_observation),
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back()->with([ 'error_header' => 'La solicitud no puede ser enviada' ]);
    }


    /**
     * @param FacultativeRequestFormRequest $request
     * @param string $rp_id
     * @param string $header_id
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function requestStore(FacultativeRequestFormRequest $request, $rp_id, $header_id)
    {
        if ($request->ajax()) {
            if ($this->repository->storeFacultative($request, decode($header_id))) {
                $header = $this->repository->getModel();

                $mail = new MailController($request->user());

                $mail->subject  = 'Solicitud de aprobación: Caso Facultativo No. ' . $header->prefix . ' - ' . $header->issue_number;
                $mail->template = 'de.request-approval';

                if ($mail->send(decode($rp_id), [ 'header' => $header ], 'COP')) {
                    $this->repository->storeSent();
                }

                return response()->json([
                    'location' => route('de.edit', compact('rp_id', 'header_id'))
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back()->with([ 'error_header' => 'La solicitud no pudo ser enviada' ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param HeaderEditFormRequest $request
     * @param string                $rp_id
     * @param string                $header_id
     * @param string                $id_facultative
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function updateFa(HeaderEditFormRequest $request, $rp_id, $header_id, $id_facultative)
    {
        if ($this->repository->updateHeaderFacultative($request, decode($header_id))) {
            $mail = new MailController($request->user());

            $this->facultativeRepository->approved = 2;
            $this->facultativeRepository->sendProcessMail($mail, $rp_id, $id_facultative, true);

            return redirect()->route('home')->with([ 'success_header' => 'La Póliza fue actualizada con éxito.' ]);
        }

        return redirect()->back()->with([ 'error_header' => 'La Póliza no pudo ser actualizada.' ])->withInput()
            ->withErrors($this->repository->getErrors());
    }


    /**
     * @param string $product
     * @param string $q
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function policies($product, $q = '')
    {
        if (request()->ajax()) {
            if ($this->repository->getPolicies($product, $q)) {
                $headers = $this->repository->getModel();

                return response()->json([
                    'headers' => $headers,
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back()->with([ 'error_header' => 'Unauthorized.' ]);
    }


    /**
     * @param Request $request
     * @param string  $rp_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadCertificate(Request $request, $rp_id)
    {
        $extensions = [ 'pdf', 'jpg', 'jpeg', 'png', 'tiff', 'tif', ];
        $size       = 5;       // 3M size
        $message    = 'Adjunte un archivo válido (' . implode(', ', $extensions) . ')';

        if ($request->hasFile('file')) {
            $id = $request->get('id');

            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
                && $this->repository->getHeaderById(decode($id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $header          = $this->repository->getModel();
                $file            = $request->file('file');

                if ($file->getClientSize() <= ( $size * 1024 * 1024 )) {
                    if (in_array(strtolower($file->getClientOriginalExtension()), $extensions)) {
                        $filename = 'crt_de_' . $header->id . '_' . date('Ymd') . '.' . $file->getClientOriginalExtension();
                        $upload   = $file->move('assets/certificates', $filename);

                        if ($upload->isFile() && $this->repository->saveCertificate($filename)) {
                            //exec('mv -f ' . public_path() . '/assets/certificates/' . $filename . ' ' . public_path() . '/assets/files/certificates/' . $filename);

                            //--edw--> if ($retailerProduct->ws && $header->is_new && ! $this->wsRepository->setDeData($header)) {
                            if (1) {
                                $message = 'El archivo se adjuntó correctamente, pero no se pudo completar la emisión. Por favor recargue la página.';
                                /**$records = $this->wsRepository->getDataRecords();
                                $emails  = $this->repository->emailsForWs();

                                Mail::send('emails.de.ws-warning', ['header' => $header, 'records' => $records], function ($m) use ($header, $emails) {
                                    $m->from(env('MAIL_USERNAME'), env('MAIL_NAME'));

                                    foreach ($emails as $email => $name) {
                                        $m->to($email, $name);
                                    }

                                    $m->subject('WS Error: Certificado No. ' . $header->prefix . '-' . $header->issue_number);
                                });

                                if (count(Mail::failures()) > 0) {
                                    $message .= ' warning.';
                                }
                                

                                goto Error;/***/
                            }

                            $location = route('de.issuance', ['rp_id' => $rp_id, 'header_id' => $id]);

                            if ($request->has('report')) {
                                $location = route('report.master', [ 'code' => 'de', 'rp_id' => $rp_id, 'type' => 'ISS' ]);
                            }

                            return response()->json([
                                'location' => $location,
                                'message'  => 'El archivo se adjuntó correctamente.',
                            ]);
                        } else {
                            $message = 'El archivo no pudo ser registrado.';
                        }
                    } else {
                        $message = 'El archivo debe ser de tipo (' . implode(', ', $extensions) . ').';
                    }
                } else {
                    $message = 'El tamaño del archivo no debe ser mayor a los ' . $size . 'M.';
                }
            }
        } else {
            $message .= '.';
        }

        Error:
        return response()->json([ 'message' => $message ], 401);
    }


    public function getAttachFile($file)
    {
        $path = base_path('public/assets/certificates/' . $file);

        return response()->download($path, null, [], null);
    }
}
