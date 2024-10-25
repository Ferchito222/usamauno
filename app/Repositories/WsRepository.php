<?php

namespace Sibas\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Sibas\Entities\De\CreditLine;
use Sibas\Entities\De\CreditLineClient;
use Sibas\Entities\De\Header;
use Illuminate\Support\Facades\DB;
use Sibas\Repositories\Adm\SettingAdmRepository;

require_once 'lib/nusoap.php';

class WsRepository
{

    protected $soapAction;

    protected $operation;

    protected $wsdl;

    protected $namespace;

    protected $client;

    protected $headers;

    protected $parameters;

    protected $data;
    protected $result;
    
    protected $errors;
    
    protected $token;

    protected $numbers;

    protected $status;

    protected $message;
    
    protected $base_uri;
    
    protected $auditoria;
    /**
     * @var Collection
     */
    protected $data_records;


    private function init()
    {
        $settingAdmRepository = new SettingAdmRepository();

        $ws_host  = 'srvws1';
        $settings = $settingAdmRepository->getSettingsByModel('ws', 'ws_host');
        $setting  = $settings->pluck('logs.0.value', 'codename');

        if ($setting->count() === 1) {
            $ws_host = $setting['ws_host'];
        }

        $this->soapAction = "http://tempuri.org/ObtieneDatosCliente";
        $this->wsdl       = 'http://' . $ws_host . '/WSAbreNet/Servicio.asmx?WSDL';
        $this->namespace  = "http://schemas.xmlsoap.org/soap/envelope/";

        $this->client = new \nusoap_client($this->wsdl, true);

        $err = $this->client->getError();

        if ($err) {
            $error_ws = $err;
        }

        $this->headers = [];
        $this->status  = 200;
    }


    /**
     * @param int    $dni
     * @param string $complement
     * @param int    $document_type
     * @return bool
     */
    public function getClient($dni, $complement, $document_type = 1)
    {
        $this->operation  = "ObtieneDatosCliente";

        $this->init();

        $this->parameters = [
            'ci'            => trim($dni),
            'complemento'   => trim($complement),
            'tipodocumento' => trim($document_type),
        ];

        $response = $this->client->call($this->operation, $this->parameters, $this->namespace, $this->soapAction,
            $this->headers, '', 'document', 'literal');

        if ($this->client->fault) {
            $error_ws = $response;
        } else {
            $err = $this->client->getError();

            if ($err) {
                $error_ws = $err;
            } else {
                $response      = json_encode($response, JSON_PARTIAL_OUTPUT_ON_ERROR);
                $response      = json_decode($response, true);
                $response      = $response['ObtieneDatosClienteResult'];
                $this->data    = [];
                $this->numbers = [];

                if (! empty($response)) {
                    $this->data = $response['Persona'];
                }

                $this->data = collect($this->data);

                if ($this->data->count() > 0) {
                    if (is_array($this->data->values()->first())) {
                        $clients    = [];
                        $first      = $this->data->first();
                        $balance    = 0;
                        $operations = collect([]);
                        $warning    = false;

                        $this->data->each(function ($d, $key) use (&$first, &$balance, &$operations, &$warning, &$clients) {
                            $clients[] = $d[ 'Nombres' ]
                                         . ' '
                                         . $d[ 'ApellidoPaterno' ]
                                         . ' '
                                         . $d[ 'ApellidoMaterno' ]
                                         . ' C.I. '
                                         . $d[ 'DocId' ]
                                         . ' Complemento '
                                         . $d[ 'Complemento' ]
                                         . ' Fecha de Nacimiento '
                                         . $d[ 'FecNac' ];

                            $result          = @array_intersect_assoc($first, $d);
                            $first           = $result;
                            $this->numbers[] = $d[ 'NroCliente' ];
                            $intersect       = array_intersect([ 'DocId', 'Complemento', 'FecNac' ], array_keys($result));
                            $keys            = count($intersect);

                            if ($keys === 3) {
                                $balance += $d['SaldoInsoluto'];
                                $op      = $this->getOperations($d);

                                if (! empty($op)) {
                                    $operations = $operations->merge($op);
                                }
                            } else {
                                $warning = true;
                            }
                        });

                        if (! $warning) {
                            $max        = $this->data->max('SaldoInsoluto');
                            $this->data = $this->data->where('SaldoInsoluto', $max)->first();

                            $this->data['SaldoInsoluto'] = $balance;
                            $this->data['Operaciones']   = $operations->toJson();
                        } else {
                            $this->status  = 500;
                            $this->message = 'ATENCIÓN: Existe más de una persona asociada al mismo Documento de Identidad: <br><br>'
                                             . implode('<br>', $clients)
                                             . '<br><br>Favor regularizar de acuerdo a procedimiento establecido e intente nuevamente';
                        }
                    } else {
                        $this->numbers[]           = $this->data[ 'NroCliente' ];
                        $this->data                = $this->data->toArray();
                        $this->data['Operaciones'] = $this->getOperations($this->data)->toJson();
                    }

                    return true;
                } else {
                    $error_ws = "No existen registros coincidentes";
                }
            }
        }

        return false;
    }


    /**
     * @param Model|Header $header
     *
     * @return bool
     */
    public function setDeData($header)
    {
        $this->operation = "RegistrarDatos";

        $warnings           = 0;
        $this->data_records = collect([]);

        foreach ($header->details as $detail) {
            if ($detail->approved) {
                $this->init();

                $rate_final = $detail->rate;
                $percentage = ( ! is_null($detail->facultative) ? $detail->facultative->percentage : 0 );

                if (! is_null($detail->facultative)) {
                    $rate_final = $detail->facultative->final_rate;
                }

                if ($header->creditProduct->slug !== 'PCA') {
                    $rate_final = $rate_final * 12;
                }

                if ($header->credit_line && ! is_null($header->creditLine)) {
                    $cl_client = $header->creditLine->clients->where('dni', $detail->client->dni)->first();

                    if ($cl_client instanceof CreditLineClient) {
                        $rate_final += (($rate_final * $cl_client->percentage) / 100);
                        $percentage = $cl_client->percentage;
                    }
                }

                $this->parameters = [
                    'tasaFinal'      => number_format($rate_final, 3),
                    'tipo'           => config('base.product_parameters.'
                        . (empty($detail->company_approval) ? 'FC' : $detail->company_approval)),
                    'nroOperacion'   => $header->operation_number,
                    'ci'             => $detail->client->dni,
                    'aprobado'       => true,
                    'eslicitado'     => ( in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ]) ? true : false ),
                    'tasaExtraPrima' => $percentage,
                    'seguroCesantia' => 0,
                    'esNuevo'        => $header->is_new,
                ];

                if ($header->creditProduct->slug === '7x5' && $detail->involuntary_unemployment) {
                    $this->parameters['seguroCesantia'] = 0.48;
                }

                $response = $this->client->call($this->operation, $this->parameters, $this->namespace, $this->soapAction,
                    $this->headers, '', 'document', 'literal');

                if ($this->client->fault) {
                    $error_ws = $response;
                    $warnings += 1;

                    $this->parameters['response'] = 'Error connection 1.';
                } else {
                    $err = $this->client->getError();

                    if ($err) {
                        $error_ws = $err;
                        $warnings += 1;

                        $this->parameters['response'] = 'Error connection 2.';
                    } else {
                        $response   = json_encode($response);
                        $response   = json_decode($response, true);
                        $response   = $response['RegistrarDatosResult'];
                        $this->data = $response;

                        $this->data['Correcto']       = filter_var($this->data['Correcto'], FILTER_VALIDATE_BOOLEAN);
                        $this->parameters['response'] = $this->data['Correcto'];

                        if (! $this->data['Correcto']) {
                            $warnings += 1;
                        }
                    }
                }

                $this->data_records->push($this->parameters);
            }
        }

        $data_records = $this->data_records->toArray();

        $header->update([
            'data_records' => $data_records,
        ]);

        if (! $header->credit_line && in_array($header->creditProduct->slug, [ 'PLT', 'PLNT' ])) {
            $header->creditLine()->update([
                'data_records' => $data_records,
            ]);
        } elseif ($header->credit_line) {
            $data_cl = $header->creditLine->data_records;

            foreach ($data_records as $key => $item) {
                $data_cl[] = $item;
            }

            $header->creditLine->update([
                'data_records' => $data_cl,
            ]);
        }

        return ( $warnings === 0 ? true : false );
    }


    /**
     * @param Model|CreditLine $credit_line
     * @param int              $operation_number
     * @param bool             $old
     *
     * @return bool
     */
    public function setDeDataUnderLine($credit_line, $operation_number, $old)
    {
        $this->operation = "RegistrarDatos";

        $warnings           = 0;
        $this->data_records = collect([]);

        foreach ($credit_line->clients as $key => $client) {
            $this->init();

            $rate = $client->rate + (($client->rate * $client->percentage) / 100);

            $this->parameters = [
                'tasaFinal'      => number_format($rate, 3),
                'tipo'           => 'Free Cover',
                'nroOperacion'   => $operation_number,
                'ci'             => $client->dni,
                'aprobado'       => true,
                'eslicitado'     => ($credit_line->policy_type === 'TE' ? true : false),
                'tasaExtraPrima' => $client->percentage,
                'seguroCesantia' => 0,
                'esNuevo'        => $old,
            ];

            $response = $this->client->call($this->operation, $this->parameters, $this->namespace, $this->soapAction,
                $this->headers, '', 'document', 'literal');

            if ($this->client->fault) {
                $error_ws = $response;
                $warnings += 1;

                $this->parameters['response'] = 'Error connection 1.';
            } else {
                $err = $this->client->getError();

                if ($err) {
                    $error_ws = $err;
                    $warnings += 1;

                    $this->parameters['response'] = 'Error connection 2.';
                } else {
                    $response   = json_encode($response);
                    $response   = json_decode($response, true);
                    $response   = $response['RegistrarDatosResult'];
                    $this->data = $response;

                    $this->data['Correcto']       = filter_var($this->data['Correcto'], FILTER_VALIDATE_BOOLEAN);
                    $this->parameters['response'] = $this->data['Correcto'];

                    if (! $this->data['Correcto']) {
                        $warnings += 1;
                    }
                }
            }

            $this->data_records->push($this->parameters);
        }

        $data_cl = $credit_line->data_records;
        
        foreach ($this->data_records->toArray() as $key => $item) {
            $data_cl[] = $item;
        }

        $credit_line->update([
            'data_records' => $data_cl,
        ]);

        return ( $warnings === 0 ? true : false );
    }


    /**
     * @param array $data
     *
     * @return array
     */
    public function getOperations($data)
    {
        $operations = collect([]);

        if (! empty($data['Operaciones'])) {
            $ops = $data['Operaciones']['Operacion'];

            if (is_array(array_values($ops)[0])) {
                $ops        = collect($ops);
                $ops        = $ops->pluck('NroOperacion');
                $operations = $ops;
            } else {
                $operations->push($ops['NroOperacion']);
            }
        }

        return $operations;
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * @return mixed
     */
    public function getNumbers()
    {
        return $this->numbers;
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * @return Collection
     */
    public function getDataRecords()
    {
        return $this->data_records;
    }
    
    public function getBaseUri()
    {
        $this->base_uri         = env('BASE_URI');
        $this->base_uri_token   = env('BASE_URI_TOKEN'); #produccion
        $this->auditoria = [
            'reasonId'  => '999',
            'username'  => request()->user()->username,
            'host'      => $_SERVER['REMOTE_ADDR'],
            'date'      => date('dmYhis'),
        ];
    }
    
    public function getWsRest2($type,$param)
    {
        $this->getBaseUri();
        
        $this->data = [];
        $client = new \GuzzleHttp\Client(['base_uri' => $this->base_uri]);
        try {
            switch ($type) {
                case 'id_cliente':
                    $response = $client->request('GET', '/v1/insurance/customer/'.$param, ['query' => $this->auditoria]);
                    break;
                case 'codigo_ruat':
                    $response = $client->request('GET', '/v1/insurance/vehicle/'.$param, ['query' => $this->auditoria]);
                    break;
                case 'placa':
                    $response = $client->request('GET', '/v1/insurance/vehicle/plate/'.$param, ['query' => $this->auditoria]);
                    break;
                case 'codigo_catastral':
                    $response = $client->request('GET', '/v1/insurance/property/'.$param, ['query' => $this->auditoria]);
                    break;
                case 'numero_serie':
                    $response = $client->request('GET', '/v1/insurance/machine/'.$param, ['query' => $this->auditoria]);
                    break;

                default:
                    break;
            }
            
            $this->data = json_decode($response->getBody()->getContents(), true);
            
            return true;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $arr = explode('response:', (string)$e->getMessage());
            
            if(count($arr) == 2){
                $this->data = json_decode($arr[1], true);
            }else{
                $this->data['errorDetail'] = 'No existe informacion';
            }
            
            if(isset($this->data['statusCode']) && $this->data['statusCode'] != 'MSSE-0000')
                return true;
        }
        return false;
    }
    public function getWsRest($type,$param)
    {
        if(!$this->newTokenFortaleza())
            return false;
        
        $this->getBaseUri();
        $complement = 'reasonId='.$this->auditoria['reasonId'].'&username='.$this->auditoria['username'].'&host='.$this->auditoria['host'].'&date='.$this->auditoria['date'];
        switch ($type) {
            case 'id_cliente':
                $response = 'ms-insurance/v1/insurance/customer/';
                break;
            case 'codigo_ruat':
                $response = 'ms-insurance/v1/insurance/vehicle/';
                break;
            case 'placa':
                $response = 'ms-insurance/v1/insurance/vehicle/plate/';
                break;
            case 'codigo_catastral':
                $response = 'ms-insurance/v1/insurance/property/';
                break;
            case 'numero_serie':
                $response = 'ms-insurance/v1/insurance/machine/';
                break;

            default:
                break;
        }

        $this->data = [];
        $url = $this->base_uri.$response.$param."?".$complement;
        $c = curl_init("$url");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$this->token->access_token,
            ]
        );
        $responseXML = curl_exec($c);
        curl_close($c);
        $this->data = json_decode($responseXML, true);
        
        if(!isset($this->data['statusCode']))
            return false;
        else
            return true;
    }

    public function storeRates($product, $param)
    {   
        $this->data=[];
        $this->getBaseUri();
        $complement = 'reasonId='.$this->auditoria['reasonId'].'&username='.$this->auditoria['username'].'&host='.$this->auditoria['host'].'&date='.$this->auditoria['date'];
        
        switch ($product) {
            case 'DE':
                $postUrl = '/v1/insurance/policy?'.$complement;
                break;
            case 'AU':
            case 'TD':
            case 'EMA':
            case 'RMA':
                $postUrl = '/v1/insurance/policyGuarantees?'.$complement;
                break;
            default:
                break;
        }
        
        $client = new \GuzzleHttp\Client(['base_uri' => $this->base_uri]);
        try {
            $sendPost = $client->post($postUrl, 
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'body'    => json_encode($param)
                   ]);
                   
            $this->data = json_decode($sendPost->getBody());
            return true;
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            
        }
        return false;
    }
    public function storeRates2($product, $param)
    {
        if(!$this->newTokenFortaleza())
            return false;
        
        $arr = [];
        $this->getBaseUri();
        $complement = 'reasonId='.$this->auditoria['reasonId'].'&username='.$this->auditoria['username'].'&host='.$this->auditoria['host'].'&date='.$this->auditoria['date'];
        switch ($product) {
            case 'DE':
                $postUrl = 'ms-insurance/v1/insurance/policy?'.$complement;
                break;
            case 'AU':
            case 'TD':
            case 'EMA':
            case 'RMA':
                $postUrl = 'ms-insurance/v1/insurance/policyGuarantees?'.$complement;
                break;
            default:
                break;
        }
        $url = $this->base_uri.$postUrl;

        $data_string = json_encode($param);
        
        $ch = curl_init("$url");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$this->token->access_token,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        $dataWs = json_decode($result);
        
        DB::table('op_ws_logs')->insert(
            [
                'op_header_id' => '', 
                'prefix' => $product, 
                'url' => $url, 
                'input_parameters' => $data_string, 
                'output_parameters' => $result, 
                'created_at' => date('Y-m-d h:i:s'), 
                'updated_at' => date('Y-m-d h:i:s'), 
            ]
        );
        
        if(isset($dataWs->statusCode)){
            if($dataWs->statusCode == 'MSSE-0000')
                $arr['err'] = ''; 
            else
                $arr['err'] = $dataWs->statusCode.' - '.$dataWs->errorDetail; 
            
        }elseif($dataWs->message)
            $arr['err'] = $dataWs->message; 
        else
            $arr['err'] = 'Error WS-FORTALEZA'; 
        /**
        echo "param entrada<pre>";
        print_r($param);
        echo "</pre>";
        echo "ress<pre>";
        print_r($dataWs);
        echo "</pre>";
        echo "mensaje<pre>";
        print_r($arr);
        echo "</pre>";
        exit();
        /**/
        return $arr;
    }
    
    /**
     * retorna token de seguridad
     * @return boolean
     */
    public function newTokenFortaleza()
    {
        $this->getBaseUri();
        
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->base_uri_token,
            'verify' => false
            ]);
        
        $headers['Content-Type']  = "application/x-www-form-urlencoded";
        $this->token = false;
        try {
            $response = $client->request('POST', '/auth/realms/Fortaleza-Middleware/protocol/openid-connect/token', [
                'headers'   => $headers,
                'form_params'      => [
                        'grant_type'=>'client_credentials',
                        'client_id'=>env('CLIENT_ID'),
                        'client_secret'=>env('CLIENT_SECRET')
                    ],
                
            ]);
           
            $response = $response->getBody()->getContents();
            
            $this->token = json_decode($response);
            
            return true;
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            dd($e, $e->getMessage());
            $this->errors = 'Error token fortaleza';
        }
        
        return false;
    }
    
    public function debitCard($param, $type='')
    {
        if(!$this->newTokenFortaleza())
            return false;
        if($type != ''){
            $card = ($type=='debit_card')?'debitCard':'creditCard';
        }else
            $card = 'debitCard';
            
        $this->getBaseUri();
        
        $this->data = [];
        $client = new \GuzzleHttp\Client([
            //'base_uri' => 'https://kctest.grupofortaleza.com.bo:8850/', #desarrollo
            'base_uri' => $this->base_uri,#produccion
            'verify' => false
                ]);
           $this->result['client'] = $client;
           $this->result['base_uri'] = $this->base_uri;
        $headers['Authorization']  = 'Bearer '.$this->token->access_token;
        try {
            $response = $client->request('GET', '/ms-insurance/v1/insurance/'.$card.'/'.$param, [
                'query' => $this->auditoria,
                'headers'   => $headers,
                    ]);
            $this->result['ressponse'] = $response;
            $this->data = json_decode($response->getBody()->getContents(), true);
            $this->result['data'] = $this->data;
            return true;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->errors = $e->getMessage();
        }
        
        return false;
    }
    
    public function sendDebitCardToCompany($headers, $lotNumber)
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://pruebas.aseguradorafortaleza.com.bo:8191/',
            'verify' => false
            ]);
        
        $json = [];
        foreach ($headers as $key => $value) {
            $json[]=[
                        "nro" => $value->issue_number,
                        "ciudad" => config('base.cod_city_company_card.'.$value->retailerCityAgency->retailerCity->city->abbreviation),
                        "numCedula" => $value->details->first()->client->dni,
                        #"ciudadCedula" => config('base.code_extension_fortaleza_company.'.$value->details->first()->client->extension),
                        "ciuCedula" => config('base.code_extension_fortaleza_company.'.$value->details->first()->client->extension),
                        "nombres" => $value->details->first()->client->first_name,
                        "apPaterno" => $value->details->first()->client->last_name,
                        "apMaterno" => $value->details->first()->client->mother_last_name,
                        "fecNacimiento" => date('dmY',strtotime($value->details->first()->client->birthdate)),
                        #"estadoCivil" => config('base.civil_status_fortaleza_company.'.$value->details->first()->client->civil_status),
                        "estCivil" => config('base.civil_status_fortaleza_company.'.$value->details->first()->client->civil_status),
                        #"generoCliente" => $value->details->first()->client->gender,
                        "genCliente" => $value->details->first()->client->gender,
                        "direccionCliente" => $value->details->first()->client->home_address,
                        "telefTrabajoCliente" => $value->details->first()->client->phone_number_office,
                        "telefCasaCliente" => $value->details->first()->client->phone_number_home,
                        #"telfMovilCliente" => $value->details->first()->client->phone_number_mobile,
                        "telMovil" => $value->details->first()->client->phone_number_mobile,
                        "correoCliente" => $value->details->first()->client->email
                    ];
        }
        echo '<pre>';
        print_r([
                                    "requestID" => "R-".str_pad($lotNumber, 4, "0", STR_PAD_LEFT),
                                    "empresaID" => "EM-006",
                                    "usuarioID" => "SUDAMERICANA-WS",
                                    "passUsuarioID" => "K5LH# hTeGi%UY",
                                    "fecha" => date('dmY'),
                                    "periodo" => date('m'),
                                    "gestion" => date('Y'),
                                    "poliza" => "TDC-1",#poliza madre
                                    "certificados" => $json,
                                    "total" => count($json)
                                ]);
        echo '</pre>';
        $contentType['Content-Type']  = "application/json";
        $this->data = false;
        try {
            $response = $client->request('POST', '/sudamericana/v1/cargacertificadostdb', [
                'headers'   => $contentType,
                'body'      => json_encode([
                                    "requestID" => "R-".str_pad($lotNumber, 4, "0", STR_PAD_LEFT),
                                    "empresaID" => "EM-006",
                                    "usuarioID" => "SUDAMERICANA-WS",
                                    "passUsuarioID" => "K5LH# hTeGi%UY",
                                    "fecha" => date('dmY'),
                                    "periodo" => date('m'),
                                    "gestion" => date('Y'),
                                    "poliza" => "TDC-1",#poliza madre
                                    "certificados" => $json,
                                    "total" => count($json)
                                ]),
            ]);
           
            $response = $response->getBody()->getContents();
            $this->data = json_decode($response);
            
            if($this->data->codigo === '00')
                return true;
            
                echo 'result Err: <pre>';
                print_r($this->data);
                echo '</pre>';
            
                return false;
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            echo 'error<pre>';
            print_r($e->getResponse()->getBody()->getContents());
            echo '</pre>';
            $this->errors = $e;
        }
        return false;
    }        
    public function testCargaCertificados()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://pruebas.aseguradorafortaleza.com.bo:8191/',
            'verify' => false
            ]);
        
        $headers['Content-Type']  = "application/json";
        $this->data = false;
        try {
            $response = $client->request('POST', '/sudamericana/v1/cargacertificadostdb', [
                'headers'   => $headers,
                /**'form_params'      => [
                        'grant_type'=>'client_credentials',
                        'client_id'=>'abrenet',
                        'client_secret'=>'f111fe86-ee93-486b-aa2d-66ab4bc842d9'
                    ],/**/
                'body'      => '{ 
                "requestID":"R-0001",
                "empresaID":"EM-006", 
                "usuarioID":"SUDAMERICANA-WS",
                "passUsuarioID":"K5LH# hTeGi%UY",
                "fecha":"01072021",
                "periodo":"06",    
                "gestion":"2021",
                "poliza":"TDC-1",
                "certificados": [
                        {
                            "nro": 1,
                            "ciudad":"LP0201",
                            "numCedula":3496897,
                            "ciudadCedula":"LP",
                            "nombres":"Juan Luis",
                            "apPaterno":"Rosales",
                            "apMaterno":"Illanes",
                            "fecNacimiento":"19091981",
                            "estadoCivil":"S",
                            "generoCliente":"M",
                            "direccionCliente":"Calle 10 Obrajes #2345",
                            "telefTrabajoCliente":22223955,
                            "telefCasaCliente":22223412,
                            "telfMovilCliente":73202681,
                            "correoCliente":"juan@mail.com"
                        }
                ],
                "total":1
            }
            ',
            ]);
           
            $response = $response->getBody()->getContents();
            dd('resultado',$response, json_decode($response));
            $this->data = json_decode($response);
            
            return true;
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            //echo '<br />--ingresando-->'.$e->getResponse()->getBody()->getContents();
            dd('error', $e);
            $this->errors = 'Error token fortaleza';
        }
        
        return false;
    }
}
