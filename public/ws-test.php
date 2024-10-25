<?php
$a = 234223234;
$b = 333;
echo "<br />------>" . $a.$b;
exit();
set_time_limit(0);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$client = new \GuzzleHttp\Client(["base_uri" => "http://172.28.30.6:8850/"]); //GuzzleHttp\Client
        /**/$sendPost = $client->post("/v1/insurance/policy?reasonId=999&username=prueba&host=234&date=19122018182323", 
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'body'    => json_encode([
                        'abrenet_policy' => 2564,
                        'number_cobis' => 7413,
                        'operation_number' => 10076171,
                        'final_rate' => 0.72,
                        'customer_ci' => '13717981TPO',
                        'charge_code' => 125,
                        'final_rate_currency' => 1,
                        'ep_final_rate' => 0,
                        'ep_charge_code' => 125,
                        'ep_currency' => 1,
                       ])
                   ]);
        /**
        $sendPost = $client->post("/v1/insurance/policyGuarantees?reasonId=999&username=prueba&host=234&date=19122018182323", 
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'body'    => json_encode([
                        'guaranteeId' => "3|CRPVA2TNODD83",
                        'abrenetPolicyGuarantees' => 27,
                        'numberCobisGuarantees' => 7413,
                        'finalRateGuarantees' => '2.2',
                        'customerCiGuarantees' => "13717981TPO",
                        'operationNumberGuarantees' => 10076171,
                        'chargeCodeGuarantees' => 950,
                        'finalRateCurrencyGuarantees' => 2,
                        'productTypeGuarantees' => 'AU',
                       ])
                   ]);
        /**/
     var_dump($sendPost->getBody());
     exit();
        $data1 = json_decode($sendPost->getBody());
        echo "<pre>";
            print_r($data1);
        echo "</pre>";
        exit();

/**try {
    $client = new \GuzzleHttp\Client([
        'base_uri' => 'http://172.28.30.6:8850/',
    ]);

    $response = $client->request('GET','/v1/insurance/customer/9022', ['query' => [
            'reasonId'=>'999',
            'username'=>'prueba',
            'host'=>$_SERVER['REMOTE_ADDR'],
            'date'=>date('dmYhis'),
            ]]);

    var_dump($response->getStatusCode());

    var_dump($response);

    var_dump($response->getBody()->getContents());
} catch (\GuzzleHttp\Exception\RequestException $e) {
    var_dump($e->getMessage());
}
exit();
/**/


/*
 * SOAP OLD
 */

require_once '../app/Repositories/lib/nusoap.php';
// require_once 'lib/nusoap.php';

//$wsdl="https://190.129.93.202:6667/ECOServices/ECOService.asmx?WSDL";
$soapAction = "http://tempuri.org/ObtieneDatosCliente";
$operation  = "ObtieneDatosCliente";
// $operation  = "RegistrarDatos";
// $operation  = "ObtieneNroLinea";
$wsdl       = 'http://192.168.11.59/WSAbreNet/Servicio.asmx?WSDL';
// $wsdl       = 'http://srvws1/WSAbreNet/Servicio.asmx?WSDL';
$namespace  = "http://schemas.xmlsoap.org/soap/envelope/";

$client = new nusoap_client($wsdl, true);

$err = $client->getError();

if ($err)
{
    $error_ws = $err;
}

// $headers = [ 'usuario' => "prueba", 'password' => "prueba" ];
// $message = [ 'usuario' => "prueba", 'password' => "prueba", 'doc_id' => '6047230', 'ext' => '2' ];

$message = [ 'tipodocumento' => '1', 'ci' => '3375667', 'complemento' => '' ];
/*$message = [
    'tasaFinal'    => '0.06',
    'tipo'         => 'FC',
    'nroOperacion' => '123123123123',
    'ci'           => '6363636',
];*/
// $message = [ 'nroOperacion' => 1080055116 ];

// $response = $client->call($operation, $message, $namespace, $soapAction, $headers, '', 'document', 'literal');
$response = $client->call($operation, $message);

if ($client->fault) {
    $error_ws = $response;
    var_dump('Ok1', $error_ws);
} else {
    $err = $client->getError();
    var_dump('Ok2', $err);

    if ($err) {
        $error_ws = $err;

        var_dump('Ok3', $error_ws);
    } else {
        $response       = json_encode($response);
        $response_a     = json_decode($response, true);
        $final_response = $response_a[ 'ObtieneDatosClienteResult' ];
        // $final_response = $response_a[ 'RegistrarDatosResult' ];
        // $final_response = $response_a[ 'ObtieneNroLineaResult' ];
        $data           = [];

        /*if (! empty($response_a[ 'ObtieneDatosClienteResult' ])) {
            $data = $response_a[ 'ObtieneDatosClienteResult' ][ 'Persona' ];
        }*/

        $data = $final_response;
        dd($data);

        dd('Ok4', $data);
    }
}

exit();

// SOAP LIB
/*$soapWrapper = new Artisaninweb\SoapWrapper\Wrapper();

$soapWrapper->add(function ($service) {
    $service
        ->wsdl('http://192.168.11.58/WSAbreNet/Servicio.asmx?WSDL')
        ->trace(true);
});

$response = $soapWrapper->call('ObtieneDatosCliente', [
    'ci'          => '1782682',
    'complemento' => '',
]);

var_dump($response);
exit();*/
