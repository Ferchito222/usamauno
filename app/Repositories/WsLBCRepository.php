<?php

namespace Sibas\Repositories;

use Carbon\Carbon;
use DateTime;
use Monolog\Logger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Sibas\Entities\De\CreditLine;
use Sibas\Entities\De\CreditLineClient;
use Sibas\Entities\De\Header;
use Illuminate\Support\Facades\DB;
use Sibas\Repositories\Adm\SettingAdmRepository;
use Sibas\Repositories\LogRepository;
use SoapClient;

require_once 'lib/nusoap.php';

class WsLBCRepository
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

    protected $token_viar;

    protected $numbers;

    protected $status;

    protected $message;
    
    protected $base_uri;

    protected $base_uri_viar;
    
    protected $auditoria;

    /**
     * @var LogRepository
     */
    private $log;
    
    protected $auditoria_collections;
    /**
     * @var Collection
     */
    protected $data_records;

    public function __construct(
        LogRepository $log
    ) {
        $this->log                       = $log;
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
        $this->token            = env('BASE_URI_TOKEN');
        $this->base_uri_viar    = env('BASE_URL_VIAR');
        $this->token_viar       = env('BASE_URI_TOKEN_VIAR');
        $this->auditoria = [
            'reasonId'  => '999',
            'username'  => 'jvera',
            'host'      => '127.0.0.1',
            'date'      => date('dmYhis'),
        ];

        $this->client   = new \nusoap_client($this->base_uri_viar, true);
        $err            = $this->client->getError();

        if ($err) {
            $error_ws = $err;
        }
        $this->headers = [];
        $this->status  = 200;
        /*$this->auditoria_collections = [
            'x-audit-agency' => '23',
            'x-audit-host' => '127.0.0.1',
            'x-audit-user' => 'ermontano',
        ];*/
    }

    public function setFormatRegister($header, $type)
    {
        $data = [];
        $cont = 0;
        if(in_array($header->creditProduct->slug, ['PMO', 'PLT']))
            $prefix = 'DH';
        else
            $prefix = 'DE';

        if($type == 'update'){
            foreach ($header->details as $detail){
                $solicitantes = [
                    "Tipo"                  => $detail->headline=='D'?'T':'C',
                    "Estado"                => 'ACEPTADA',
                    "Nombres"               => $detail->client->first_name,
                    "ApellidoPaterno"       => $detail->client->last_name,
                    "ApellidoMaterno"       => $detail->client->mother_last_name,
                    "ApellidosEsposo"       => $detail->client->married_name,
                    "NumeroDocumento"       => $detail->client->dni,
                    "TipoDocumento"         => $detail->client->document_type,
                    "ExpedicionDocumento"   => $detail->client->extension,
                    "Nacionalidad"          => $detail->client->nationality,
                    "LugarNacimiento"       => $detail->client->locality,
                    "FechaNacimiento"       => date('Y-m-d H:i:s', strtotime($detail->client->birthdate)),
                    "Sexo"                  => $detail->client->gender=='M'?'MAS':'FEM',
                    "Ocupacion"             => $detail->client->occupation_description,
                    "Direccion"             => $detail->client->home_address,
                    "TelefonoDomicilio"     => $detail->client->phone_number_home,
                    "TelefonoOficina"       => $detail->client->phone_number_office,
                ];
                $responses = json_decode($detail->response->response);
                foreach ($responses as $key => $response){
                    if (array_key_exists('response_specification', $response)) {
                        $response_specification = $response->response_specification;
                    } else {
                        $response_specification = '';
                    }
                    
                    $resp['Pregunta'.$key] = $response->response;
                    $resp['DetallePregunta'.$key] = $response_specification;
                }

                $archivos = [
                    "NombreAdjunto"      =>  '',
                    "tipo"               =>  '',
                    "Archivo"            =>  '',
                ];
            }
            $data['ListaSolicitantes'] = array_merge($solicitantes, $resp);
            $data['ListaArchivos'] = $archivos;
        }else{

            $data['Seguro'] = [
                "EntidadSigla"          => $_ENV['LBC_ENTIDAD'],
                "IdTransaccion"         => $prefix.'-'.$header->user->city->abbreviation.'-'.$header->issue_number,
                "TipoCobertura"         => $header->ad_coverage_id==1?'INDIVIDUAL':'CODEUDOR',
                "TipoCartera"           => $header->ad_credit_product_id==3||$header->ad_credit_product_id==6||$header->ad_credit_product_id==8?'LICITADA':'NO LICITADA',
                "Monto"                 => $header->amount_requested,
                "Moneda"                => $header->currency=='BS'?'Bs.':'$us',
                "Plazo"                 => $header->term,
                "Lugar"                 => $header->retailerCityAgency->retailerCity->city->abbreviation,
                "Destino"               => $header->creditProduct->name,
                "FechaSolicitud"        => date('Y-m-dTH:i:s', strtotime($header->date_issue)),
                "FechaRegistro"         => date('Y-m-dTH:i:s', strtotime($header->created_at)),
                "UsuarioRegistro"       => $_ENV['LBC_USUARIO'],
                
            ];
            foreach ($header->details as $detail){
                $solicitantes = [                    
                    "Tipo"                      => $detail->headline=='D'?'T':'C',
                    "Estado"                    => null,
                    "Nombres"                   => $detail->client->first_name,
                    "ApellidoPaterno"           => $detail->client->last_name,
                    "ApellidoMaterno"           => $detail->client->mother_last_name,
                    "ApellidosEsposo"           => $detail->client->married_name,
                    "NumeroDocumento"           => $detail->client->dni,
                    "TipoDocumento"             => $detail->client->document_type,
                    "ExpedicionDocumento"       => $detail->client->extension=='SE'?'NA':$detail->client->extension=='PO'?'PT':$detail->client->extension=='TJ'?'TR':$detail->client->extension=='PN'?'PA':$detail->client->extension,
                    "Nacionalidad"              => $detail->client->nationality==''?'BOL':$detail->client->nationality,
                    "LugarNacimiento"           => $detail->client->birth_place,
                    "Estatura"                  => $detail->client->height,
                    "Peso"                      => $detail->client->weight,
                    "FechaNacimiento"           => date('Y-m-d H:i:s', strtotime($detail->client->birthdate)),
                    "Sexo"                      => $detail->client->gender=='M'?'MAS':'FEM',                    
                    "Ocupacion"                 => $detail->client->occupation_description,                    
                    "Direccion"                 => $detail->client->home_address,
                    "TelefonoDomicilio"         => $detail->client->phone_number_home,
                    "TelefonoOficina"           => $detail->client->phone_number_office,
                    "SaldoDeudor"               => $detail->balance,
                    "MonedaSaldoDeudor"         => 'Bs.',
                    /*"PorcentajeCredito"         => '',                    
                    "MontoActualAcumuladoUSD"   => '',
                    "NumeroCredito"             => '',*/
                ];
                
                $responses = json_decode($detail->response->response);
                foreach ($responses as $key => $response){
                    if (array_key_exists('response_specification', $response)) {
                        $response_specification = $response->response_specification;
                    } else {
                        $response_specification = '';
                    }
                    
                    $resp['Pregunta'.$key] = $response->response;
                    $resp['DetallePregunta'.$key] = $response_specification;
                }
                
                $beneficiario['Beneficiario'] = [ 
                    "Nombres"               => $detail->beneficiary->first_name,
                    "ApellidoPaterno"       => $detail->beneficiary->last_name,
                    "ApellidoMaterno"       => $detail->beneficiary->mother_last_name,
                    "ApellidosEsposo"       => '',
                    "Parentesco"            => 'PND',
                    "Telefono"              => '',
                ];
                $data['ListaSolicitantes'][] = array_merge($solicitantes, $resp, $beneficiario);
                //$data['ListaSolicitantes']['Beneficiario'] = ;
            }
            
            $data['Beneficiario'] = $beneficiario;
        }
        $cont++;
        /*echo '<pre>';
        var_dump($data, json_encode($data));
        echo '</pre>';*/
        //exit();
        return $data;
    }

    /**
     * funcion registra cobranzas a FORTALEZA por WS
     */
    public function registerProducton($headers, $type, $method, $url)
    {
        $this->getBaseUri();

        $this->auditoria_collections['Authorization'] = $this->token;
        $this->auditoria_collections['Content-Type'] = 'application/json';
        $data = '';
        
        if($type == 'register')
            $data = $this->setFormatRegister($headers, $type);
        elseif($type == 'document')
            $data = $headers;
            
        /*echo '<pre>aaaa';
        print_r($this->base_uri);
        echo '</pre>';
        echo '<pre>bbb';
        print_r($this->auditoria_collections);
        echo '</pre>';
        echo '<pre>ccc';
        print_r(json_encode($data));
        echo '</pre>';
        echo '</pre>';
        echo '<pre>ddd';
        print_r($type);
        echo '</pre>';
        echo '</pre>';
        echo '<pre>eee';
        print_r($url);
        echo '</pre>';
        echo '</pre>';
        echo '<pre>fff';
        print_r($method);
        echo '</pre>';*/

            
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
        
        return false;
    }

    public function getIso8601($date)
    {
        $dt = new DateTime($date);
        
        return $dt->format('Y-m-d\TH:i:s.').substr($dt->format('u'),0,3).'Z';
    }

    public function setFormatRegisterVIAR($header, $type)
    {
        $data = [];
        $codeExtensionLBC = config('base.code_extension_lbc_company');
        $activities_lbc_company = config('base.activities_lbc_company');
        foreach ($header->details as $detail){
            $solicitantes = [
                'ApellidoPaterno' => $detail->client->last_name,
                'ApellidoMaterno' => $detail->client->mother_last_name,
                'ApellidoCasada' => $detail->client->married_name,
                'PrimerNombre' => $detail->client->first_name,
                'SegundoNombre' => '',
                'TipoDocumentoIdentidad' => 'CI',
                'NumeroDocumento' => $detail->client->dni,
                'Extension' => $codeExtensionLBC[$detail->client->extension],
                'Edad' => $detail->client->age,
                'Nacionalidad' => '000140',
                'FechaNacimiento' => date('Y-m-d\TH:i:s\Z', strtotime($detail->client->birthdate)),
                'EstadoCivil' => $detail->client->civil_status=='F'?'U':$detail->client->civil_status,
                'Genero' => $detail->client->gender=='M'?'01':'02',
                'TelefonoCelular' => $detail->client->phone_number_home,
                'Profesion' => 'GCPNOPRO',
                'ActividadPrincipal' => array_key_exists($detail->client->occupation_description, $activities_lbc_company) ? $activities_lbc_company[$detail->client->occupation_description] : '',
                'Pais' => 'PAIS0029',
                'Departamento' => 'CBB',
                'Ciudad' => 'CIU0327',
                'Zona' => $detail->client->zone,
                'Calle' => $detail->client->home_address,
                'Numero' => $detail->client->home_number,
                'PerfilSalud' => [
                    'CambioBeneficiarios' => 'SI',
                    'Peso' => $detail->client->weight.' Kilogramos',
                    'Estatura' => $detail->client->height.' Metros',
                    'TensionArterial' => $detail->client->pressure,
                    'CapacidadReducida' => 'NO',
                    'MotivoCapacidadReducida' => '',
                ],
            ];
            
            foreach ($detail->beneficiaries as $beneficiary){
                $beneficiario['BeneficiarioExtEntity'] = [ 
                    "NombresApellidos"  => $beneficiary->first_name.' '.$beneficiary->last_name.' '.$beneficiary->mother_last_name,
                    "Parentesco"        => $beneficiary->relationship,
                    "Participacion"     => $beneficiary->percentage,
                ];
                $bnf[] = array_merge($beneficiario);
            }
            
            
            //$data['ListaSolicitantes']['Beneficiario'] = ;
        }

        $clientes = [            
            'ClienteExtEntity'  => [               
                "Ciudad"                      => 'CIU0327', //COCHABAMBA
                "NroCertificadoCobertura"     => $header->prefix.'-'.$header->issue_number,
                "NroPoliza"                   => $header->issue_number,
                "FechaInicioVigencia"         => date('Y-m-d', strtotime($header->date_issue)),
                "FechaFinVigencia"            => date('Y-m-d', strtotime($header->date_issue . ' + ' . $header->term . ' MONTH')),
                //"MedioPago"                   => null,
                "FechaRegistro"               => date('Y-m-d\TH:i:s\Z', strtotime($header->created_at)),
                "Prima"                       => $header->total_premium,
                "MonedaPrima"                 => '001',
                //"PlanAlternativa"             => null,
                "ValorComercial"              => '10000',
                // "DatosAdicionales"            => null,
                // "Sucursal"                    => null,
                // "CodAgencia"                  => null,
                // "Agencia"                     => null,
                // "NombreFuncionario"           => null,
                // "NumeroTarjeta"               => null,
                "Asegurado"                   => $solicitantes,
                "ListaBeneficiarios"          => $bnf,
                //"ListaDependientes"           => null,
            ]
        ];
        try{
            // 'Token'                 => $_ENV['BASE_URI_TOKEN_VIAR'],
            $data['RegistrarClientesSA'] = [
                'registraCliente'   => [
                    'Token'                 => $this->token_viar,
                    'IdentificadorEnvio'    => $header->id,
                    'IdEmpresa'             => 'EMP100',
                    'NroPolizaMadre'        => 'A4007747',
                    'Nombre'                => 'COOPERATIVA SAN ANTONIO',
                    'UsuarioRegistro'       => strtoupper($header->user->username),
                    'ListaClientes'         => $clientes
                ]
            ];        
        }catch(\Exception $e){
            dd($e->getMessage());
        }
        // echo '<pre>';
        // var_dump($data, json_encode($data));
        // echo '</pre>';
        // exit();
        return $data;
    }

    public function registerProductonVIAR($headers, $type, $method, $url)
    {
        
        $this->getBaseUri();

        // $this->auditoria_collections['Authorization'] = $this->token;
        $this->auditoria_collections['Content-Type'] = 'application/json';
        
        $data = [];
        $warnings = 0;
        $data = $this->setFormatRegisterVIAR($headers, $type);
        
        try{

            //$wsdl = 'https://DESARROLLO.LBC.BO/LBC/SERVICIOS/WSBDWSANANTONIO/WSWHOLESALESANANTONIO.ASMX?WSDL'; //DESARROLLO
            //$wsdl = 'https://servicios.lbc.bo/LBC/Servicios/WSBDWSanAntonio/WSWholesaleSanAntonio.asmx'; //PRODUCCIÓN
            $wsdl = $this->base_uri_viar;
            $client = new \nusoap_client($wsdl, true);

            
            $error = $client->getError();
            if ($error) {
                die("Error al crear el cliente: " . $error);
            }
            $client->soap_defencoding = 'UTF-8'; // Establecer la codificación
            $client->decode_utf8 = false; // Para mantener la codificación

            $client->setHeaders([
                'Cookie' => 'cookiesession1=678A3E12E3BFAD4FCAB196269287BDA8',
                'SOAPAction' => 'http://tempuri.org/RegistrarClientesSA'
            ]);

            $result = $this->convertArrayToXml($data);
            $response = $client->call('RegistrarClientesSA', $result, '', '', false, true);
            
            if ($client->fault) {
                $error_ws = $response;
                $warnings += 1;

                $this->parameters['response'] = 'Error connection 1.';
                return false;
            } 
            
            $err = $client->getError();
            
            if ($err) {
                $error_ws = $err;
                $warnings += 1;
                $this->parameters['response'] = 'Error connection 2.';
                return false;
            }
            
            $this->log->setLog('VI Registrar Produccion', $data, $headers->user, Logger::INFO, $response);

            if($response){
                $this->data = $response['RegistrarClientesSAResult'];
                
                return true;
            }
            return false;
        }catch(\Exception $e){
            $responseBodyAsString = $e->getMessage();
            // $responseBodyAsString = $response->getBody()->getContents();
            $this->errors = json_decode($responseBodyAsString);
        }

        return false;
    }

    public function registerPagoVIAR($headers, $type, $method, $url)
    {
        
        $this->getBaseUri();

        // $this->auditoria_collections['Authorization'] = $this->token;
        $this->auditoria_collections['Content-Type'] = 'application/json';
        
        $data['RegistrarPagoSA'] = [
            'registraPago' => [
                'IdEmpresa' => 'EMP100',
                'NroCertificadoCobertura' => $headers->prefix.'-'.$headers->issue_number,
                'NroPolizaMadre' => 'A4007747',
                'IdentificadorPago' => $headers->id,
                'FechaRegistro' => date('Y-m-d', strtotime($headers->date_issue)),
                'UsuarioRegistro' => strtoupper($headers->user->username)
            ]
        ];
        $warnings = 0;

        try{

            
            // $wsdl = 'https://DESARROLLO.LBC.BO/LBC/SERVICIOS/WSBDWSANANTONIO/WSWHOLESALESANANTONIO.ASMX?WSDL'; //DESARROLLO
            // $wsdl = 'https://servicios.lbc.bo/LBC/Servicios/WSBDWSanAntonio/WSWholesaleSanAntonio.asmx'; //PRODUCCIÓN
            $wsdl = $this->base_uri_viar;
            $client = new \nusoap_client($wsdl, true);


            $error = $client->getError();
            if ($error) {
                die("Error al crear el cliente: " . $error);
            }
            $client->soap_defencoding = 'UTF-8'; // Establecer la codificación
            $client->decode_utf8 = false; // Para mantener la codificación

            $client->setHeaders([
                'Cookie' => 'cookiesession1=678A3E12E3BFAD4FCAB196269287BDA8',
                'SOAPAction' => 'http://tempuri.org/RegistrarPagoSA'
            ]);

            $result = $this->convertArrayToXml($data);
            
            $response = $client->call('RegistrarPagoSA', $result, '', '', false, true);
                        
            if ($client->fault) {
                $error_ws = $response;
                $warnings += 1;

                $this->parameters['response'] = 'Error connection 1.';
                return false;
            } 
            
            $err = $client->getError();
            
            if ($err) {
                $error_ws = $err;
                $warnings += 1;
                $this->parameters['response'] = 'Error connection 2.';
                return false;
            }
            
            $this->log->setLog('VI Registrar Cobranza', $data, $headers->user, Logger::INFO, $response);
            
            if($response){
                $this->data_records = $response['RegistrarPagoSAResult'];
                return true;
            }
            return false;
        }catch(\Exception $e){
            $responseBodyAsString = $e->getMessage();
            // $responseBodyAsString = $response->getBody()->getContents();
            $this->errors = json_decode($responseBodyAsString);
        }

        return false;
    }

    public function getHeaderById($header_id){
        $header = Header::with([
            'details.client',
            'details.beneficiaries',
            'details.response',
            'user.city', 
        ])->where('id', '=', $header_id)->get();
        return $header; 
    }

    public function convertArrayToXml($data, $index = 0){
        $keys = $data;
        $result = "";
        foreach($data as $key => $value){
            $closeTag = "";
            if(!ctype_digit($key) && !is_int($key)){
                if($index == 0){
                    $result = $result . '<' . $key .' xmlns="http://tempuri.org/" >';
                }else{
                    $result = $result . '<' . $key .'>';
                }
                $closeTag = '</'.$key.'>';
            }
            if(is_array($value)){
                $text = $this->convertArrayToXml($value, $index + 1);
            }else{
                $text = $value;
            }
            $result = $result . $text . $closeTag;
        }
        return $result;
    //     return '<RegistrarClientesSA xmlns="http://tempuri.org/">
    //     <registraCliente>
    //       <IdentificadorEnvio>1714673443</IdentificadorEnvio>
    //       <IdEmpresa>EMP100</IdEmpresa>
    //       <NroPolizaMadre>A1234567</NroPolizaMadre>
    //       <Nombre>COOPERATIVA SAN ANTONIO</Nombre>
    //       <UsuarioRegistro>JVERA</UsuarioRegistro>
    //       <Token>Token2024</Token>
    //       <ListaClientes>
    //         <ClienteExtEntity>
    //           <Ciudad>CIU0327</Ciudad>
    //           <NroCertificadoCobertura>VD-1</NroCertificadoCobertura>
    //           <NroPoliza>1</NroPoliza>
    //           <FechaInicioVigencia>2024-05-02</FechaInicioVigencia>
    //           <FechaFinVigencia>2025-05-02</FechaFinVigencia>
    //           <MedioPago></MedioPago>
    //           <Prima>98.0</Prima>
    //           <MonedaPrima>001</MonedaPrima>
    //           <PlanAlternativa></PlanAlternativa>
    //           <ValorComercial>10000</ValorComercial>
    //           <DatosAdicionales></DatosAdicionales>
    //           <Sucursal></Sucursal>
    //           <CodAgencia></CodAgencia>
    //           <Agencia></Agencia>
    //           <NombreFuncionario></NombreFuncionario>
    //           <NumeroTarjeta></NumeroTarjeta>
    //           <FechaRegistro>2024-05-02</FechaRegistro>
    //           <Asegurado>
    //               <ApellidoPaterno>JUAREZ</ApellidoPaterno>
    //               <ApellidoMaterno>ROMAN</ApellidoMaterno>
    //               <ApellidoCasada></ApellidoCasada>
    //               <PrimerNombre>FERMIN</PrimerNombre>
    //               <SegundoNombre></SegundoNombre>
    //               <TipoDocumentoIdentidad>CI</TipoDocumentoIdentidad>
    //               <NumeroDocumento>13717981</NumeroDocumento>
    //               <Extension>PO</Extension>
    //               <Edad>58</Edad>
    //               <Nacionalidad>000140</Nacionalidad>
    //               <FechaNacimiento>1965-07-07</FechaNacimiento>
    //               <EstadoCivil>S</EstadoCivil>
    //               <Genero>01</Genero>
    //               <TelefonoCelular>2222222</TelefonoCelular>
    //               <Profesion>GCPNOPRO</Profesion>
    //               <ActividadPrincipal>RQWRRWQRW</ActividadPrincipal>
    //               <Pais>PAIS0029</Pais>
    //               <Departamento>CBB</Departamento>
    //               <Ciudad>CIU0327</Ciudad>
    //               <Zona>ewqeqw</Zona>
    //               <Calle>HUAYLLANI CALLE INNOMINADA CAS A NRO. S.N.</Calle>
    //               <Numero></Numero>
    //               <PerfilSalud>
    //                   <CambioBeneficiarios>SI</CambioBeneficiarios>
    //                   <Peso>0 Kilogramos</Peso>
    //                   <Estatura>0 Metros</Estatura>
    //                   <TensionArterial>100 mm Hg</TensionArterial>
    //                   <CapacidadReducida>NO</CapacidadReducida>
    //                   <MotivoCapacidadReducida></MotivoCapacidadReducida>
    //               </PerfilSalud>
    //           </Asegurado>
    //           <ListaBeneficiarios>
    //               <BeneficiarioExtEntity>
    //                   <NombresApellidos>ASDAS DASD ASD</NombresApellidos>
    //                   <Parentesco>SOBRINO</Parentesco>
    //                   <Participacion>100</Participacion>
    //               </BeneficiarioExtEntity>
    //           </ListaBeneficiarios>
    //         </ClienteExtEntity>
    //       </ListaClientes>
    //     </registraCliente>
    //   </RegistrarClientesSA>';
    }
}