<?php

namespace Sibas\Http\Controllers\Client;

use Carbon\Carbon;
use Sibas\Entities\Client;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\De\CreditLineRepository;
use Sibas\Repositories\De\DetailRepository;
use Sibas\Repositories\De\HeaderRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\WsRepository;
use Illuminate\Support\Facades\URL;

class ClientController extends Controller
{

    /**
     * @var ClientRepository
     */
    protected $repository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var WsRepository
     */
    protected $ws;

    /**
     * @var DetailRepository
     */
    protected $detailRepository;
    /**
     * @var HeaderRepository
     */
    protected $headerRepository;
    /**
     * @var CreditLineRepository
     */
    protected $creditLineRepository;


    public function __construct(
        ClientRepository $repository,
        RetailerProductRepository $retailerProductRepository,
        HeaderRepository $headerRepository,
        DetailRepository $detailRepository,
        WsRepository $ws,
        CreditLineRepository $creditLineRepository
    ) {
        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->headerRepository          = $headerRepository;
        $this->detailRepository          = $detailRepository;
        $this->ws                        = $ws;
        $this->creditLineRepository = $creditLineRepository;
    }


    use DataTrait;


    /**
     * Return Client by search
     *
     * @param string      $rp_id
     * @param string|null $header_id
     *
     * @return \Illuminate\Http\RedirectResponse|ClientController
     */
    public function search($rp_id, $header_id = null)
    {
        $client          = null;
        $retailerProduct = null;

        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $ws              = $retailerProduct->ws;
            $number          = date('U');
            $account         = [
                'type'     => 'IC',
                'currency' => 'BS',
                'number'   => ( $number += 1 ),
                'total'    => implode(',', [ $number, ( $number + 1 ), ( $number + 2 ), ]),
            ];
            if($this->headerRepository->getHeaderById(decode($header_id))) {
                $header = $this->headerRepository->getModel();
                if (request()->ajax() && request()->has('refresh')) {
                    if ($ws) {
                        $id = decode(request()->get('refresh'));

                        $detail = $header->details->filter(function ($d) use ($id) {
                                    return $d->client->id == $id;
                                })->first();

                        $dni = $detail->client->dni;
                        $complement = $detail->client->complement;
                        $document_type = $detail->client->document_type_bank;

                        if ($this->ws->getClient($dni, $complement, $document_type)) {
                            $data = $this->ws->getData();
                            $status = $this->ws->getStatus();

                            if ($status === 200) {
                                $balance = empty($data['SaldoInsoluto']) ? 0 : $data['SaldoInsoluto'];

                                Client::where('id', $id)
                                        ->update(['debit_balance' => $balance]);

                                return response()->json([
                                            'balance' => $balance,
                                ]);
                            }
                        }
                    }
                    return response()->json([], 500);
                }

                if ($header->credit_line 
                        && !$this->creditLineRepository->checkClientInsured($header->data_records['number_line'], 
                                request()->get('s_dni'), request()->get('s_complement'))) {
                    return redirect()
                                    ->back()
                                    ->withInput()
                                    ->with(['err_client' => 'El cliente no existe en la línea o no cuenta con seguro. En caso de que se trate de un cliente sin seguro dentro de la línea por favor proceda con el siguiente.']);
                }
            }
            $ws = false;
            if ($ws) {
                $s_document_type = 1;

                if (request()->has('s_document_type')) {
                    $s_document_type = request()->get('s_document_type');
                } elseif (request()->has('refresh')) {
                    $id = decode(request()->get('refresh'));

                    $detail = $header->details->filter(function ($d) use ($id) {
                        return $d->client->id == $id;
                    })->first();

                    $s_document_type = $detail->client->document_type_bank;
                }
                
                $flag = false;
                switch ($retailerProduct->companyProduct->product->code) {
                    case 'tj':
                        $flag = $this->ws->debitCard(request()->get('s_dni'));
                        //$flag = true;
                        break;
                    default:
                        $flag = $this->ws->getWsRest('id_cliente',request()->get('s_dni'));
                        //$flag = true;
                        break;
                }
                
                if ($flag) {
                    $data   = $this->ws->getData();
                    //$data       = dataExmaple($retailerProduct->companyProduct->product->code, true);
                    $header_d   = (isset($header))?$header:[];
                    
                    if ($data['errorDetail'] != null) {
                        $mess = $data['errorDetail'];

                        return redirect()->back()->with([ 'err_client' => $mess ]);
                    }
                    
                    $activity = $retailerProduct->activities->where("code",$data['response']['occupationCode'])->first();
                    
                    $client                         = new Client();
                    $client->last_name              = $data['response']['firstSurname'];
                    $client->mother_last_name       = $data['response']['secondSurname'];
                    $client->first_name             = $data['response']['firstName'];
                    $client->married_name           = (isset($data['response']['marriedSurname']))?$data['response']['marriedSurname']:'';
                    $client->birthdate              = Carbon::parse($data['response']['birthDate'])->toDateString();
                    $client->dni                    = getDni($data['response']['documentId']);
                    $client->extension              = getExtension($data['response']['documentId']);
                    $client->ad_activity_id         = ($activity != null)?$activity->id:'';
                    $client->occupation_description = $data['response']['occupation'];
                    $client->home_address           = $data['response']['homeAddress'];
                    $client->business_address       = $data['response']['businessName'];
                    
                    if($data['response']['civilStateCode'])
                        $client->civil_status = config('base.civil_status_code_fortaleza.'.$data['response']['civilStateCode']);

                    $operations = $this->operationList($data, $retailerProduct, $header_d);
                    $client->operations             = json_encode($operations);
                        
                    if($retailerProduct->companyProduct->product->code == 'de'){
                        
                        $client->debit_balance                  = $this->sumCumulo($data, $retailerProduct, $header_d);
                        $client->economic_activity_type_code    = $data['response']['economicActivityTypeCode'];
                        $client->economic_activity_type         = $data['response']['economicActivityType'];
                        $client->detail_operations              = json_encode($data['response']['operationList']);
                        
                        if($client->debit_balance < 0)
                        return redirect()->back()->with([ 'error_client' => 'El monto solicitado '.$header_d->amount_requested.' '.$header_d->currency.' no coincide con el monto de la línea.'])
                            ->withInput()->withErrors($this->repository->getErrors());
                    }
                    
                    if($retailerProduct->companyProduct->product->code == 'tj'){
                        
                        if($data['response']['civilStateCode'])
                            $client->civil_status = config('base.civil_status_code_fortaleza.'.$data['response']['civilStateCode']);
                        
                        $client->type_card = request()->get('s_card');
                        
                        if(request()->get('s_card') == 'DC')
                            $client->debit_cards = (($data['response']['debitCards']))?json_encode($data['response']['debitCards']):'';
                        else{
                            $dataCard = [];
                            if($this->ws->debitCard(request()->get('s_dni'),'credit_card')){
                            //if(1){
                                $dataCredit = $this->ws->getData();
                                //$dataCredit = dataExmaple('tj_credit', true);
                                $dataCard = $dataCredit?$dataCredit['response']:[];
                            }
                            $client->credit_cards = json_encode($dataCard);
                        }
                        
                        $client->accounts       = ($data['response']['accounts'])?json_encode($data['response']['accounts']):'';    
                    }
                    
                    $client->gender         = ($data['response']['genderCode']==1)?'M':'F';
                    $client->code                   = request()->get('s_dni');
                    $client->type                   = (trim($data['response']['customerType']) == 'NATURAL')?'N':'L';
                            
                    $client->document_type_bank     = $s_document_type;

                    if (request()->has('refresh')) {
                        Client::where('id', decode(request()->get('refresh')))
                            ->update([
                                'operations' => $client->operations
                            ]);

                        return redirect()->back();
                    }
                } elseif (request()->has('refresh')) {
                    return redirect()->back();
                }
            } elseif ($this->repository->getClientByDni(request()->get('s_dni'), request()->get('s_complement'), true)) {
                $client = $this->repository->getModel();


                if (is_null($client->document_type_bank)) {
                    $client->document_type_bank = ($client->extension === 'PE' ? 16 : ($client->document_type === 'CI' ? 1 : 0));
                }

                $client->addresses    = ! empty($client->addresses) ? $client->addresses : json_encode([]);
                $client->operations   = ! empty($client->operations) ? $client->operations : json_encode([]);

                $addresses = json_decode($client->addresses, true);

                if (count($addresses) > 0) {
                    $client->home_address     = $addresses[0];
                    $client->business_address = key_exists(1, $addresses) ? $addresses[1] : '';
                }
            }

            if ($client instanceof Client) {
                if ($retailerProduct->companyProduct->product->code === 'de') {
                    return redirect()->route('de.detail.create',
                        compact('rp_id', 'header_id'))->with([ 'client' => $client, ]);
                }

                $url = URL::previous();

                return redirect($url)->with([ 'client' => $client, 'account' => $account, ])->withInput();
            }
        }

        return redirect()->back()->with([ 'error_client' => 'El Cliente no existe' ])->withInput()
            ->withErrors($this->repository->getErrors());
    }
    
    public function operationList($arr, $retailerProduct, $header) 
    {
        $operations = [];
        if(isset($arr['response']['operationList'])){
            foreach ($arr['response']['operationList'] as $key => $value) {
                
                # Nros de operacion
                if($retailerProduct->companyProduct->product->code != 'de'){
                    if($value['operationType'] != 'BAJO LINEA')
                        $operations[] = $value['operation'];
                }else{
                    $operations[] = $value['operation'];
                }
            }
            
        }
        
        return $operations;
    }
    
    public function sumCumulo($arr, $retailerProduct, $header) 
    {
        $sumCumulo = 0;
        $exchangeRate = $retailerProduct->retailer->exchangeRate;
        
        $amount_requested = ($header->currency == 'BS') ? $header->amount_requested / $exchangeRate->bs_value : $header->amount_requested;
        if(isset($arr['response']['operationList'])){
            foreach ($arr['response']['operationList'] as $key => $value) {
                    
                #Saldo deudor
                if($retailerProduct->companyProduct->product->code == 'de' && $value['disbursed']==1){
                    if($header->creditProduct->slug=='PCA' && $amount_requested <= 20000){
                        if ($value['origin'] == 'TRJ')
                                $sumCumulo+=$this->returnMonto($value, 'approvedBalance', $header->currency, $exchangeRate);
                    }else{
                        if ($value['origin'] == 'TRJ') {
                            
                            if($header->creditProduct->slug=='PCA')
                                $sumCumulo+=$this->returnMonto($value, 'approvedBalance', $header->currency, $exchangeRate);
                            
                        }elseif($value['origin'] == 'BOL' && $value['operationType'] == 'FUERA DE LINEA'){
                            $sumCumulo+=$this->returnMonto($value, 'approvedBalance', $header->currency, $exchangeRate);
                        }elseif($value['operationType'] == 'BAJO LINEA'){
                            $sumCumulo+=$this->returnMonto($value, 'approvedBalance', $header->currency, $exchangeRate);
                            //$sumCumulo+=$this->returnMonto($value, 'operationBalance', $header->currency, $exchangeRate);
                        }elseif($value['operationType'] == 'FUERA DE LINEA'){
                            $sumCumulo+=$this->returnMonto($value, 'operationBalance', $header->currency, $exchangeRate);
                        }
                    }
                }
            }
            
        }
        
        if($header->currency == 'USD'){
            $sumCumulo=($sumCumulo*$exchangeRate->bs_value);
            if($header->creditProduct->slug=='PLT' || $header->creditProduct->slug=='PLNT')
                $sumCumulo = $sumCumulo-($header->amount_requested*$exchangeRate->bs_value);
        }else{
            if($header->creditProduct->slug=='PLT' || $header->creditProduct->slug=='PLNT')
                $sumCumulo = $sumCumulo-$header->amount_requested;
        }
        return $sumCumulo;
    }

    public function returnMonto($value,$key, $currency, $exchangeRate)
    {
        $monto = 0;
        if ($currency == 'USD') {
            if ($value['operationCurrency'] == 1)//Bs
                $monto = ($value[$key] / $exchangeRate->bs_value);
            if ($value['operationCurrency'] == 2)//USD
                $monto = $value[$key];
        }else {
            if ($value['operationCurrency'] == 1)//Bs
                $monto = $value[$key];
            if ($value['operationCurrency'] == 2)//USD
                $monto = ($value[$key] * $exchangeRate->bs_value);
        }
        return $monto;
    }
    
    /**
     * test ws fortaleza
     * @param type $type
     * @param type $code_client
     */
    public function newConection($type, $code_client)
    {
        
        switch ($type) {
            case 'token':
                if ($this->ws->newTokenFortaleza($code_client)){
                    
                    echo '<br />--ingresandoFIN-->';
                    dd($this->ws->getToken());
                }else
                    echo '<br />--error respuesta token-->';
            break;
            case 'debit_card':
            case 'credit_card':
                if ($this->ws->debitCard($code_client, $type)){
                    
                    echo '<br />--ingresandoFIN-->';
                    dd($this->ws->getData(), $this->ws->getResult());
                }else{
                    echo '<br />--error respuesta token-->';
                    dd($this->ws->getErrors(), $this->ws->getResult());
                }
                    
            break;
            case 'card_carga_certificado':
                if ($this->ws->testCargaCertificados()){
                    
                    echo '<br />--ingresandoFIN-->';
                    dd($this->ws->getData());
                }else
                    echo '<br />--error respuesta token-->';
            break;
            case 'id_cliente':
            case 'id_cliente_data':
            case 'codigo_ruat':
            case 'placa':
            case 'codigo_catastral':
            case 'numero_serie':
                echo '<br />---->'.$type;
                if ($this->ws->getWsRest($type, $code_client)){
                    echo '<br />--ingresandoFIN-->';
                    dd($this->ws->getData());
                }else{
                    echo '<br />--error respuesta token-->';
                    dd($this->ws->getData());
                }
            break;
            case 'test_rest':
                $wsFortalezaRepository = new \Sibas\Repositories\WsFortalezaRepository();
                echo '<br />---->'.$type;
                if ($wsFortalezaRepository->getWs($code_client, $type)){
                    echo '<br />--ingresandoFIN-->';
                    dd($wsFortalezaRepository->getData());
                }else{
                    echo '<br />--error respuesta token-->';
                    dd($wsFortalezaRepository->getData());
                }
            break;
            case 'payment_between_date':
                $request = request()->all();
                $wsFortalezaRepository = new \Sibas\Repositories\WsFortalezaRepository();
                echo '<br />---->'.$type;
                if ($wsFortalezaRepository->getPaymentBetweenDate($request['date_ini'], $request['date_fin'])){
                    echo '<br />--ingresandoFIN-->';
                    dd($wsFortalezaRepository->getData());
                }else{
                    echo '<br />--error respuesta -->';
                    dd($wsFortalezaRepository->getErrors());
                }
            break;
            case 'register_secure_debit_card':
                $request = request()->all();
                
                $wsFortalezaRepository = new \Sibas\Repositories\WsFortalezaRepository();
                $collectionRepository = new \Sibas\Repositories\Tj\CollectionRepository();
                
                $collections = [];
                if($collectionRepository->getCollectionsByNotCharged('DC'))
                    $collections = $collectionRepository->getModel();
                
                    echo '<br />---->'.$type;
                if ($wsFortalezaRepository->registerSecureDebitCard($collections)){
                    echo '<br />--ingresandoFIN-->';
                    dd($wsFortalezaRepository->getData());
                }else{
                    echo '<br />--error respuesta -->';
                    dd($wsFortalezaRepository->getErrors());
                }
            break;
            case 'read_cards':
                if($data = $this->repository->loadFile($_GET['file_name'])){
                    $data = $data[0];
                    foreach ($data as $key => $value) {
                        echo '--'.$key.'-->'.$value['cage'];
                        if($this->ws->debitCard((int)$value['cage'])){
                            $ress = $this->ws->getData();
                            var_dump($ress);
                            $data[$key]+=$this->repository->setFormData($ress);
                        }else{
                            $data[$key]+=$this->repository->setFormData(false);
                        }
                    }
                    
                    $this->repository->sendExcelAttached('completeInfo', $data, setting('emailTo'));
                }
            break;

            default:
                break;
        }
        
    }

    public function getClients()
    {    
        if($this->retailerProductRepository->getRetailerProductById(1487341817)){
            $retailerProduct = $this->retailerProductRepository->getModel();
            
            $clients = Client::select('id','code','complement','extension','dni' )
            ->whereRaw('LENGTH(dni) >= 5')
            ->whereRaw('LENGTH(dni) <= 6')
            ->get();
            $clientWs = [];
            
            foreach($clients->toArray() as $key => $value){
                
                if($this->ws->getWsRest('id_cliente',$value['code'])){
                //if(1){
                    $data = $this->ws->getData();
                    //$data       = dataExmaple('de', true);
                }elseif($this->ws->debitCard($value['code'])){
                    $data = $this->ws->getData();
                }else
                    $data = [];
                
                if(count($data)>0 && isset($data['response']['documentId'])){
                    if(getDni($data['response']['documentId']) != $value['dni']){
                        unset($value['full_name']);
                        unset($value['full_dni']);
                        unset($value['operation_numbers']);
                        $value['dni_ws'] = getDni($data['response']['documentId']);
                        $value['extension_ws'] = getExtension($data['response']['documentId']);
                        $clientWs[] = $value;
                    }
                }    
            }
            
            return view('client.list', compact('clientWs', 'retailerProduct'));
        }
        return redirect()->back();
    }    
}
