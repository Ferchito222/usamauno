<?php

namespace Sibas\Repositories;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Sibas\Entities\De\CreditLine;
use Sibas\Entities\De\CreditLineClient;
use Sibas\Entities\De\Header;
use Illuminate\Support\Facades\DB;
use Sibas\Repositories\Adm\SettingAdmRepository;

require_once 'lib/nusoap.php';

class WsFortalezaRepository
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
    
    protected $auditoria_collections;
    /**
     * @var Collection
     */
    protected $data_records;

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
            'username'  => 'ermontano',
            'host'      => '127.0.0.1',
            'date'      => date('dmYhis'),
        ];
        $this->auditoria_collections = [
            'x-audit-agency' => '23',
            'x-audit-host' => '127.0.0.1',
            'x-audit-user' => 'ermontano',
        ];
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

    public function getWS($param, $type='')
    {
        if(!$this->newTokenFortaleza())
            return false;
        echo 'no error token';
        
        $this->getBaseUri();
        
        $this->data = [];
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->base_uri,#produccion
            'verify' => false
                ]);
        $headers['Authorization']  = 'Bearer '.$this->token->access_token;
        try {
            $response = $client->request('GET', '/ms-insurance/v1/insurance/customer/'.$param, [
                'query' => $this->auditoria,
                'headers'   => $headers,
                    ]);
            
            $this->data = json_decode($response->getBody()->getContents(), true);
            
            return true;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            dd($e);
            $this->errors = $e->getMessage();
        }
        
        return false;
    }

    /**
     * funcion retorna datos de cobranzas WS en base a un rango de fechas
     */
    public function getPaymentBetweenDate($initial_date, $final_date)
    {#Y-m-d fechas
        if(!$this->newTokenFortaleza())
            return false;
        
        $this->data = [];        
        $query = [
            'initialDate'  => $this->getIso8601($initial_date.date('H:i:s')),
            'finalDate'  => $this->getIso8601($final_date.date('H:i:s')),
        ];
        
        $this->auditoria_collections['Authorization'] = 'Bearer '.$this->token->access_token;
        
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->base_uri,
            'verify' => false
                ]);
        
        try {
            $response = $client->request('GET', '/ms-insurance/v1/insurance/debitCard/payment', [
                'query' => $query,
                'headers'   => $this->auditoria_collections,
                    ]);
            
            $this->data = json_decode($response->getBody()->getContents(), true);
           
            return true;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            
            $this->errors = json_decode($responseBodyAsString);
        }
        
        return false;
    }

    /**
     * funcion registra cobranzas a FORTALEZA por WS
     */
    public function registerSecureDebitCard($collections)
    {
        
        if(!$this->newTokenFortaleza())
            return false;

        $this->auditoria_collections['Authorization'] = 'Bearer '.$this->token->access_token;
        $this->auditoria_collections['Content-Type'] = 'application/json';
        
        $data = $this->setFormatSecureDebitCard($collections);
        
        echo '<pre>';
        print_r($this->base_uri);
        echo '</pre>';
        echo '<pre>';
        print_r($this->auditoria_collections);
        echo '</pre>';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        
        
        $client = new \GuzzleHttp\Client([
            'base_uri' =>  $this->base_uri,
            'verify' => false
            ]);
        
        try {
            $response = $client->request('POST', '/ms-insurance/v1/insurance/debitCard/payment', [
                'headers'   => $this->auditoria_collections,
                'body'      => json_encode($data),
            ]);
           
            $this->data = json_decode($response->getBody()->getContents(), true);
            
            return true;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            
            $this->errors = json_decode($responseBodyAsString);
        }
        
        return false;
    }

    public function setFormatSecureDebitCard($collections)
    {
        $data = [];
        $cont = 0;
        foreach ($collections as $key => $collection){
            if ($collection->header->details->first()->card_number 
                    /**&&in_array($collection->header->details->first()->client->code, [
                    1130,4039,4482,5327,5516,6423,8514,48514,40361,29675,52001,48539,28287,48573,48534,48549,48223,48586,36919,48590,48502,37513,48602,48611,48560
                    ])/** */
                    ) {
                $data[] = [
                    "paymentCode"       => $collection->id,
                    "customerCode"      => $collection->header->details->first()->client->code,
                    "certificateNumber" => $collection->header->issue_number,
                    "cardNumber"        => $collection->header->details->first()->card_number,
                    "customerName"      => htmlspecialchars($collection->header->details->first()->client->full_name),
                    "amount"            => number_format($collection->fee_amount, 1, '.', ''),
                    "accountNumber"     => $collection->header->details->first()->savings_bank,
                ];
            }
            $cont++;
        }
        return $data;
    }

    public function getIso8601($date)
    {
        $dt = new DateTime($date);
        
        return $dt->format('Y-m-d\TH:i:s.').substr($dt->format('u'),0,3).'Z';
    }
    
}
