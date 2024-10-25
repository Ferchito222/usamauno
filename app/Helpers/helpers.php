<?php

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

function encrypt($value)
{
    return Crypt::encrypt($value);
}

function decrypt($value)
{
    try {
        return Crypt::decrypt($value);
    } catch (DecryptException $e) {
        //
    }
}

function encode($value)
{
    return Hashids::encode($value);
}

function decode($value)
{
    $value = Hashids::decode($value);

    return ( count($value) === 1 ? $value[ 0 ] : 0 );
}

function dateToFormat($birthdate)
{
    if (! is_null($birthdate)) {
        $carbon = new \Carbon\Carbon();
        $date   = $carbon->createFromTimestamp(strtotime($birthdate));

        return $date->format('d/m/Y');
    }

    return null;
}

function monthTranslated($month)
{
    $months = [
        1  => 'Enero',
        2  => 'Febrero',
        3  => 'Marzo',
        4  => 'Abril',
        5  => 'Mayo',
        6  => 'Junio',
        7  => 'Julio',
        8  => 'Agosto',
        9  => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];

    return $months[ $month ];
}

function date_format_report($date)
{
    return Carbon::createFromTimestamp(strtotime($date))->format('d/m/Y H:i a');
}

function random_username($full_name)
{
    $full_name   = explode(' ', $full_name);
    $first_part  = key_exists(2, $full_name) ? substr($full_name[2], 0, 1) : substr($full_name[0], 0, 1);
    $second_part = key_exists(2, $full_name) ? substr($full_name[2], 2, 3) : substr($full_name[0], 2, 3);
    $third_part  = key_exists(2, $full_name) ? $full_name[0] : $full_name[1];

    return strtolower(trim($first_part) . trim($second_part) . trim($third_part));
}

/**
 * @param \Sibas\Entities\De\Header $header
 * @param \Sibas\Entities\De\Detail $detail
 *
 * @return string
 */
function getDeStateCompany($header, $detail)
{
    $state = '';
    //if ($header->issued && ! $header->facultative) {
    if ($header->issued && is_null($detail->facultative)) {
        $state = 'Aprobado Free Cover';
    } elseif ($header->facultative
              && ( ! is_null($detail->facultative)
                   && $detail->facultative->state === 'PR'
                   && $detail->facultative->approved )
    ) {
        $state = 'Aprobado';
    } elseif ($header->facultative
              && ( ! is_null($detail->facultative)
                   && $detail->facultative->state === 'PR'
                   && ! $detail->facultative->approved )
    ) {
        $state = 'Rechazado';
    } elseif (! $header->issued
              && $header->facultative
              && ( ! is_null($detail->facultative)
                   && $detail->facultative->state === 'PE'
                   && $detail->facultative->observations->count() === 0 )
    ) {
        $state = 'Pendiente';
    } elseif (! $header->issued
              && $header->facultative
              && ( ! is_null($detail->facultative)
                   && $detail->facultative->state === 'PE'
                   && $detail->facultative->observations->count() > 0 )
    ) {
        $state = 'Observado';

        if ($detail->facultative->observations->last()->response) {
            $state = 'Subsanado Pendiente';
        }
    }

    return $state;
}

/**
 * @param \Sibas\Entities\De\Header $header
 * @param \Sibas\Entities\De\Detail $detail
 *
 * @return string
 */
function getDeReasonStateCompany($header, $detail)
{
    $reason = '';

    if ($header->facultative
        && ( ! is_null($detail->facultative)
             && $detail->facultative->state === 'PR'
             && $detail->facultative->approved )
    ) {
        $reason = 'Aprobado';
    } elseif ($header->facultative
              && ( ! is_null($detail->facultative)
                   && $detail->facultative->state === 'PR'
                   && ! $detail->facultative->approved )
    ) {
        $reason = 'Rechazado';
    } elseif (! $header->issued
              && $header->facultative
              && ( ! is_null($detail->facultative)
                   && $detail->facultative->state === 'PE'
                   && $detail->facultative->observations->count() > 0 )
    ) {
        $reason = $detail->facultative->observations->last()->state->state;
    }

    return $reason;
}

/**
 * @param \Sibas\Entities\De\Detail $detail
 *
 * @return string
 */
function getDeObservationFacultative($detail)
{
    $observation = '';

    if (! is_null($detail->facultative)) {
        if ($detail->facultative->state === 'PR') {
            $observation = $detail->facultative->observation;
        } elseif ($detail->facultative->state === 'PE' && $detail->facultative->observations->count() > 0) {
            $observation = $detail->facultative->observations->last()->observation;
        }
    }

    return $observation;
}

/**
 * @param \Sibas\Entities\De\Header $header
 * @param \Sibas\Entities\De\Detail $detail
 *
 * @return string
 */
function getDeDaysInProcess($header, $detail)
{
    $days = '';

    if ($header->issued && $header->facultative
        && ( ! is_null($detail->facultative) && $detail->facultative->state === 'PR' )
    ) {
        $days = $detail->facultative->updated_at->startOfDay()->diffInDays($header->created_at->startOfDay());
    } elseif ($header->issued && ! $header->facultative) {
        $days = $header->date_issue->startOfDay()->diffInDays($header->created_at->startOfDay());
    } elseif (! $header->issued && $header->facultative
              && ( ! is_null($detail->facultative)
                   && $detail->facultative->state === 'PE'
                   && $detail->facultative->observations->count() === 0 )
    ) {
        $dt   = Carbon::create(null, null, null, 0);
        $days = $dt->diffInDays($header->created_at->startOfDay());
    } elseif (! $header->issued && $header->facultative
              && ( ! is_null($detail->facultative)
                   && $detail->facultative->state === 'PE'
                   && $detail->facultative->observations->count() > 0 )
    ) {
        $days = $detail->facultative->observations->last()->created_at->startOfDay()
            ->diffInDays($header->created_at->startOfDay());
    }

    return $days;
}

/**
 * @param \Sibas\Entities\De\Header $header
 *
 * @return int
 */
function getDeTotalDuration($header)
{
    $days = 0;

    if ($header->issued && ! is_null($header->date_issue)) {
        $days = $header->date_issue->startOfDay()->diffInDays($header->created_at->startOfDay());
    } elseif (! $header->issued) {
        $dt   = Carbon::create(null, null, null, 0);
        $days = $dt->diffInDays($header->created_at->startOfDay());
    }

    return $days;
}

function mountByCurrency($currency, $mount, $exchange)
{
    if($currency == 'USD')
        return $mount/$exchange->bs_value;
    else
        return $mount;
}

function dataExmaple($prod, $result=true)
{
    #1 sin actividad
    #2 dependiente
    #3 independiente
    switch ($prod) {
        case 'de':
            return [
                'statusCode' => 'MSSE-0000',
                'response' =>
                [
                    "customerId" => '205371',
                    "firstName" => "OMAR",
                    "firstSurname" => "GONZALEZ",
                    "secondSurname" => "RODRIGUEZ",
                    "marriedSurname" => null,
                    "businessName" => null,
                    "birthDate" => "1989-06-22T04:00:00.000+0000",
                    "documentIdTypeCode" => 1,
                    "documentIdType" => "CEDULA DE IDENTIDAD",
                    "documentId" => "8306449LP",
                    "civilStateCode" => 1,
                    "civilState" => "SOLTERO(A)",
                    "genderCode" => 1,
                    "gender" => "MASCULINO",
                    "nit" => null,
                    "customerType" => "NATURAL",
                    "complementId" => null,
                    "occupation" => "ADMINISTRADOR(A) DE EMPRESAS",
                    "occupationCode" => "65191",
                    "homeAddress" => "SAN JORGE AVENIDA ARCE ESQ. CO RDERO EDIFICIO BANCO FORTALEZA",
                    "workAddress" => "SAN JORGE AVENIDA ARCE ESQ. CORDERO EDIFICIO BANCO FORTALEZA NRO. 2799 PISO 3 BLOQUE SN NR",
                    "economicActivityTypeCode" => 2,
                    "economicActivityType" => "DEPENDIENTE",
                    "operationList" => [
                        [
                            "operation" => '3456',
                            "operationType" => "BAJO LINEA",
                            "origin" => "TRJ",
                            "operationCurrency" => 1,
                            "operationBalance" => 0,
                            "approvedBalance" => '11000.0',
                            "disbursed" => 1,
                            "status" => 2,
                            "disbursementDate" => "2019-07-18T04:00:00.000+0000",
                            "paymentFrequency" => 0,
                        ]
                    ],

                ],
                "errorDetail" => null,
                "httpStatus" => "OK",
                "rootCause" => null,
                "validStatusCode" => true,
            ];
            break;
        case 'au':
            return [
                'statusCode' => 'MSSE-0000',
                'response' =>
                [
                    'type_vehicle' => 'CA',
                    'type_vehicle_desc' => 'CAMION',
                    'brand' => 'FORD',
                    'brand_id' => 10,
                    'model' => 'F-14000',
                    'circulation_plaza' => '', //
                    'year' => '2006',
                    'number_plate' => '3617YXT',
                    'use' => 'PARTICULAR',
                    'engine' => 'D2296022906948203',
                    'chassis' => '9BFXXXLM9JDB84133',
                    'color' => 'AZUL',
                    'displacement' => '4000', //
                    'origin' => 'ESTADOS UNIDOS', //
                    'tonnage_capacity' => '8.00',
                    'number_seat' => '',
                    'insured_value' => '52654.12',
                    'currency' => 1,
                    'guaranteeId' => '3|1766707',
                ],
                'errorDetail' => ''
            ];
            break;
        case 'td':
            return [
                'statusCode' => 'MSSE-0000',
                'response' => [
                    'type' => 'TERRENO',
                    'property_use' => '',
                    'cadastral_code' => 20109900002,
                    'city' => 'LA PAZ',
                    'zone' => 'VILLA COPACABANA',
                    'location' => 'MURILLO',
                    'situation' => '',
                    'insured_value' => '6399.37',
                    'currency' => 2,
                ],
                'errorDetail' => '',
            ];
            break;
        case 'rma':
        case 'ema':
            return [
                'statusCode' => 'MSSE-0000',
                'response' =>
                [
                    'property_use' => null,
                    'description' => 'INDUSTRIAL VARIOS AÑO 117 MODELO VARIOS COLOR VARIOS',
                    'serial_number' => '7EFM2',
                    'city' => 'ORURO',
                    'insured_value' => '270132.35',
                    'currency' => 1,
                    'guaranteeId' => '6|4444',
                ],
                'errorDetail' => ''
            ];
            break;
        case 'tj':
            if($result==true){
                return [
                "statusCode" => "MSSE-0000",
                "response" => [
                  "customerId" => 190376,
                  "firstName" => "MELANY GISELLE",
                  "firstSurname" => "NU¥EZ.",
                  "secondSurname" => "MÉNDEZ",
                  "marriedSurname" => null,
                  "businessName" => null,
                  "birthDate" => "1986-11-15T04:00:00.000+0000",
                  "documentIdTypeCode" => 1,
                  "documentIdType" => "CEDULA DE IDENTIDAD",
                  "documentId" => "59895251T",
                  "civilStateCode" => 1,
                  "civilState" => "SOLTERO(A)",
                  "genderCode" => 2,
                  "gender" => "MASCULINO",
                  "nit" => null,
                  "customerType" => "NATURAL ",
                  "complementId" => null,
                  "occupation" => "LICENCIADO(A)",
                  "occupationCode" => "65191",
                  "homeAddress" => "SAN JORGE AVENIDA ARCE EDIFICI O FORTALEZA ANEXO NRO. 2799 PI",
                  "workAddress" => "SAN JORGE AVENIDA ARCE EDIFICIO FORTALEZA ANEXO NRO. sn PISO PB NRO. DEPARTAMENTO 1",
                  "debitCards" => [
                    0 => [
                      "customerId" => 190376,
                      "accountNumber" => "400052603",
                      "customerName" => "NU¥EZ. MÉNDEZ MELANY GISELLE",
                      "cardNumber" => "627802XXXXXX6035",
                      "dueDate" => "022023",
                      "customerType" => "T",
                      "productType" => null,
                      "cardName" => "MELANY GISELLE LANDA LAREDO",
                      "cardTypeCode" => 1,
                      "cardType" => "PRINCIPAL",
                      "accounts" => [
                        0 => [
                          "accountNumber" => "2051491547",
                          "type" => "CAJA DE AHORRO  ",
                        ],
                        1 => [
                          "accountNumber" => "2051514375",
                          "type" => "CAJA DE AHORRO  ",
                        ],
                      ],
                    ],
                  ],
                  "accounts" => [
                    0 => [
                      "accountNumber" => "2051491547",
                      "type" => "CAJA DE AHORRO  ",
                    ],
                    1 => [
                      "accountNumber" => "2051514375",
                      "type" => "CAJA DE AHORRO  ",
                    ],
                  ],
                ],
                "errorDetail" => null,
                "httpStatus" => "OK",
                "rootCause" => null,
                "validStatusCode" => true,
              ];
            }else
            return [
                    "statusCode" => "MSSE-0000",
                    "response" => [
                      "customerId" => 190376,
                      "firstName" => "MAYRA YOVANA",
                      "firstSurname" => "VARGAS",
                      "secondSurname" => "HUANCA",
                      "marriedSurname" => null,
                      "businessName" => null,
                      "birthDate" => "1981-12-27T04:00:00.000+0000",
                      "documentIdTypeCode" => 1,
                      "documentIdType" => "CEDULA DE IDENTIDAD",
                      "documentId" => "4093868CH",
                      "civilStateCode" => 1,
                      "civilState" => "SOLTERO(A)",
                      "genderCode" => 2,
                      "gender" => "MASCULINO",
                      "nit" => null,
                      "customerType" => "NATURAL ",
                      "complementId" => null,
                      "occupation" => null,
                      "occupationCode" => "52323",
                      "homeAddress" => "VALLE HERMOSO CALLE HERRERA CA SA NRO. 70",
                      "workAddress" => "CENTRAL CALLE JUNIN CASA NRO. 76",
                      "debitCards" => [],
                      "accounts" => [
                        0 => [
                          "accountNumber" => "4051206025",
                          "type" => "CAJA DE AHORRO  ",
                        ],
                      ],
                    ],
                    "errorDetail" => null,
                    "httpStatus" => "OK",
                    "rootCause" => null,
                    "validStatusCode" => true,
                  ];
            break;
        case 'tj_credit':
            if($result==true){
                return [
                "statusCode" => "MSSE-0000",
                "response" => [
                    [
                        "customerId" => 190376,
                        "accountNumber" => "9064566",
                        "customerName" => "VARGAS HUANCA MAYRA YOVANA",
                        "cardNumber" => "525655XXXXXX9494",
                        "dueDate" => "122022",
                        "customerType" => "T",
                        "productType" => "CLASICA INTERNACIONAL",
                    ],
                ],
                "errorDetail" => null,
                "httpStatus" => "OK",
                "rootCause" => null,
                "validStatusCode" => true,
              ];
            }else
                return [];
            break;
        case 'response_ws_send_collections_to_fortaleza':
            if($result==true){
                return [
                    "statusCode" => "MSSE-0000",
                    "response" => "Se realizo el registro correctamente ",
                    "errorDetail" => null,
                    "httpStatus" => "OK",
                    "rootCause" => null,
                    "validStatusCode" => true,
                ];
            }else
                return [];
            break;
        case 'response_ws_update_tj_collections':
            
                return [
                "statusCode" => "MSSE-0000",
                "response" => [
                    0 => [
                        "paymentCode" => 985,
                        "customerCode" => 4482,
                        "certificateNumber" => 66,
                        "cardNumber" => "345343XXXXXX4455",
                        "customerName" => "MONICA SANDRA DORADO SALAZAR",
                        "amount" => 14.0,
                        "currencyCode" => 1,
                        "accountNumber" => "2051192799",
                        "transactionNumber" => 577125,
                        //"transactionNumber" => 686240,
                        "transactionDate" => "2020-10-21T04:00:00.000+0000",
                        "transactionAmount" => 14.0,
                        "observation" => null,
                        "paymentStatus" => 0,
                      ],
                  1 => [
                    "paymentCode" => 602,
                    "customerCode" => 4039,
                    "certificateNumber" => 66,
                    "cardNumber" => "531920XXXXXX8378",
                    "customerName" => "MONICA SANDRA DORADO SALAZAR",
                    "amount" => null,
                    "currencyCode" => null,
                    "accountNumber" => "2052015821",
                    "transactionNumber" => null,
                    "transactionDate" => null,
                    "transactionAmount" => null,
                    "observation" => null,
                    "paymentStatus" => 1,
                  ],
                  2 => [
                    "paymentCode" => 986,
                    "customerCode" => 4482,
                    "certificateNumber" => 66,
                    "cardNumber" => "345343XXXXXX4455",
                    "customerName" => "MONICA SANDRA DORADO SALAZAR",
                    "amount" => 14.0,
                    "currencyCode" => 1,
                    "accountNumber" => "2051192799",
                    "transactionNumber" => 577126,
                    "transactionDate" => "2020-10-21T04:00:00.000+0000",
                    "transactionAmount" => 14.0,
                    "observation" => null,
                    "paymentStatus" => 0,
                  ],
                ],
                "errorDetail" => null,
                "httpStatus" => "OK",
                "rootCause" => null,
                "validStatusCode" => true,
              ];
           
            break;
        default:
            break;
    }
}

function franquicia($city,$vehicle_type)
{
    $vh = $vehicle_type;
     switch ($city) {
         case 'LP':
         case 'EAT':
         case 'CB':
         case 'OR':
         case 'TJ':
             if($vh == 'CAPR')#liviano particular
                 return 50;
             if($vh == 'CAPU')#liviano publico
                 return 50;
             if($vh == 'CBPR')#pesado particular
                 return 200;
             if($vh == 'CBPU')#pesado publico
                 return 200;
             break;
         case 'CH':
             if($vh == 'CAPR')#liviano particular
                 return 25;
             if($vh == 'CAPU')#liviano publico
                 return 25;
             if($vh == 'CBPR')#pesado particular
                 return 200;
             if($vh == 'CBPU')#pesado publico
                 return 200;
             break;
         case 'SC':
             if($vh == 'CAPR')#liviano particular
                 return 50;
             if($vh == 'CAPU')#liviano publico
                 return 100;
             if($vh == 'CBPR')#pesado particular
                 return 250;
             if($vh == 'CBPU')#pesado publico
                 return 300;
             break;

         default:
             return false;
             break;
     }
     return 0;
}

function getProcessLoaders($number)
{
    if($number>=1 && $number<=15){
        return 'progress-bar-danger';
    }elseif($number>=16 && $number<=30){
        return 'progress-bar-warning';
    }elseif($number>=31 && $number<=45){
        return 'progress-bar-success';
    }elseif($number>=46 && $number<=60){
        return 'progress-bar-striped';
    }elseif($number > 61){
        return 'progress-bar-info';
    }
    return 'progress-bar-warning';
    
}

function dateYear($date)
{
    $arr = explode('/', $date);
    
    return getMonth((int)$arr[1]).' '.$arr[2];
}

function getMonth($nro)
{
    switch ($nro) {
        case 1:
            return 'Enero';
            break;
        case 2:
            return 'Febrero';
            break;
        case 3:
            return 'Marzo';
            break;
        case 4:
            return 'Abril';
            break;
        case 5:
            return 'Mayo';
            break;
        case 6:
            return 'Junio';
            break;
        case 7:
            return 'Julio';
            break;
        case 8:
            return 'Agosto';
            break;
        case 9:
            return 'Septiembre';
            break;
        case 10:
            return 'Octubre';
            break;
        case 11:
            return 'Noviembre';
            break;
        case 12:
            return 'Diciembre';
            break;

        default:
            return '';
            break;
    }
}

function getProducts()
{
    $products = [];
    foreach (auth()->user()->retailerUser->retailer->retailerProducts as $key => $value) {
            if ($value->type == 'MP' && $value->active) {
                if (auth()->user()->profile->first()->slug === 'SEP' || auth()->user()->profile->first()->slug === 'REP') {
                    if (auth()->user()->retailerUser->products()->where('ad_products.id', $value->companyProduct->product->id)->count() === 1) 
                        $products[$value->companyProduct->product->code] = $value->companyProduct->product->name;
                }
            }
        }
        return $products;
}

function getAgeByIssued($created_at, $birthdate)
{
    $date = Carbon::parse($created_at);
    
    return (int)$date->diffForHumans($birthdate, $date);
}

function checkBeneficiaries($detail)
{
    if($detail->beneficiaries->count() > 0) {
        $percentage = 0;
        foreach ($detail->beneficiaries as $key => $value) {
            $percentage += $value->percentage;
        }
        
        if ($percentage == 100)
            return true;
        else
            return false;
    }

    return false;
}

function getAge($birthDate)
{
    $birthDate = explode("/", $birthDate);
    
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[0], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
    
    return $age;
}

function prefixByProduct($product, $policy_number)
{
    switch ($product) {
        case 'tj':
            $arr = explode('-',$policy_number);
            return $arr[0].'-'.substr($arr[1],  0, 2);
            
            break;

        default:
            break;
    }

    return false;
}

function cards($arr)
{
    $cont = 0;
    $array[$cont]['id']      = '';
    $array[$cont]['name']    = 'Seleccione';
    if($arr != '')
        foreach (json_decode($arr) as $key => $value) {
            $cont++;
            $array[$cont]['id']      = $value->cardNumber;
            $array[$cont]['name']    = $value->cardNumber;
        }
    
    return $array;
}

function getExtension($data)
{
    $cities = ['LP', 'OR', 'PO', 'CB', 'CH', 'TJ', 'SC', 'BE', 'PA', 'EAT', 'PE'];
    if($data != ''){
        $ext = substr($data, -2);
        if(in_array($ext, $cities)){
            return $ext;
        }else
        return 'SE';
    }else
        return 'SE';
    
}

function getDni($data)
{
    $cities = ['LP', 'OR', 'PO', 'CB', 'CH', 'TJ', 'SC', 'BE', 'PA', 'EAT', 'PE'];
    
    if(getExtension($data) == 'SE')
        return $data;
    else{
        $dniWs = str_replace(getExtension($data),'',$data);
        return $dniWs;
    }
}

function getDetailOperations($arr)
{
    $result = [];
    if($arr != ''){
        $operations = json_decode($arr, true);
        foreach($operations as $keyO => $valueO){
            $result[$valueO['operation']] = $valueO;
        }
    }
    return $result;
}


function numberTjCertificate($issue_number, $policy_number)
{
    $certificate = $issue_number;
    if(!empty($policy_number)){
        $arr = explode("-",$policy_number);
        $certificate = $arr[0]."-".$arr[2]."-".$issue_number;
    }else{
        $certificate = $issue_number;
    }

    return $certificate;
}

function signatureTjPolicy($numSing, $policyNumber)
{
    if(!empty($policyNumber)){
        $arr = explode("-",$policyNumber);
        $numPol = $arr[2];
        $pathSign = "public/images/".$numSing."_firma_".$numPol.".png";
    }else{
        $pathSign = "public/images/".$numSing."_firma_56.png";;
    }

    return base_path($pathSign);
}

function getSlugPermissions($array)
{
    $permisions = [];
    foreach ($array as $key => $value){
        $permisions[] = $value->slug;
    }
    
    return $permisions;
}

function validRp($rp_id)
{
    $rp = new \Sibas\Repositories\Retailer\RetailerProductRepository();
    if($rp->getRetailerProductById(decode($rp_id))){
        return $rp->getModel();
    }
    return false;
}

function getUrlQr($rp_id, $header)
{
    return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/print/'.getToken($rp_id, encode($header->id), $header);
}

function getToken($rp_id, $header_id, $header)
{
    $userRepository = new Sibas\Repositories\UserRepository();
    $user_id = 0;
    if($user = $userRepository->getUserPublic())
        if($user->count()==1 && $user->first()->permissions->count()==1)
            $user_id = $user->first()->id;
            
            
    $seconds = $header->policy_end->diffInSeconds(carbon::now());
    
    $payload = [
        'iat' => time(), 
        'exp' => time() + $seconds,
        'expires_date' => Carbon::now()->addSecond($seconds),
        'data' => [ 
            'rid' => $rp_id,
            'hid' => $header_id,
            'uid' => $user_id
        ]
    ];
    
    return \Firebase\JWT\JWT::encode($payload, env('JWT_SECRET'), 'HS256');
};

function isFacultativeAccidentalDeath($header, $detail)
{
    if(!in_array($header->creditProduct->slug, ['PMO', 'PLT']) && $header->facultative && ! is_null($detail->facultative)){
        if($detail->facultative->state === 'PR' && $detail->facultative->accidental_death == true)
            return true;
    }
                
    return false;
}

function setting($optional)
{
    return config('base.setting.'.$optional);
}