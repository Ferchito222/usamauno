<?php

namespace Sibas\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Collections\BaseCollection;
use Sibas\Entities\Certificate;
use Sibas\Entities\Client;
use Sibas\Entities\De\Header;
use Sibas\Entities\ExchangeRate;
use Sibas\Entities\ProductParameter;
use Sibas\Entities\Retailer\Cashier;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\User;
use Sibas\Http\Controllers\MailController;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $relations = [];

    /**
     * @var BaseCollection
     */
    private $collection;

    /**
     * @var Collection
     */
    private $selectOption;

    /**
     * @var Carbon
     */
    public $carbon;

    protected $errors;

    protected $result;

    protected $data;

    protected $array;

    /**
     * @var array
     */
    protected $fieldName;

    /**
     * @var string
     */
    protected $reasonImc;

    /**
     * @var string
     */
    protected $reasonResponse;

    /**
     * @var string
     */
    protected $reasonCumulus;

    /**
     * @var string
     */
    protected $reasonInmueble;

    /**
     * @var string
     */
    protected $reasonYear;

    /**
     * @var string
     */
    protected $reasonAmount;

    /**
     * @var string
     */
    protected $reasonProperty;

    /**
     * @var string
     */
    protected $reasonActivity;

    /**
     * @var Collection
     */
    protected $records;

    /**
     * @var Model
     */
    protected $fa;

    /**
     * @var Model
     */
    protected $header;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var int
     */
    public $approved = null;

    /**
     * @var Certificate|null
     */
    public $certificate = null;


    public function __construct()
    {
        $this->collection   = new BaseCollection();
        $this->selectOption = $this->collection->selectOption();
        $this->carbon       = new Carbon();
        $this->response     = false;

        $this->reasonImc      = 'El Titular :name no cumple con el IMC. ';
        $this->reasonResponse = 'El Titular :name no cumple con el Cuestionario de Salud. ';
        $this->reasonCumulus  = 'El monto total acumulado del :name es :cumulus Bs. y supera el monto maximo ' . 'permitido. Monto maximo permitido :amount_max Bs. ';
        $this->reasonInmueble = 'El monto acumulado del riesgo asegurado :riesgo_asegurado del Titular :name es :cumulus Bs. y supera el monto maximo permitido. Monto maximo permitido para este caso :amount_max Bs. ';
        $this->reasonYear     = 'El Vehículo con placa :license_plate tiene una antiguedad mayor a :year_max años. ';
        $this->reasonAmount   = 'El valor asegurado del Vehículo con placa :license_plate excede el máximo valor permitido. Valor permitido: :amount_max USD. ';
        $this->reasonProperty = 'El valor asegurado del Riesgo asegurado :matter_insured excede el máximo valor permitido. Valor permitido: :amount_max USD. ';
        $this->reasonActivity = 'La ocupación del Titular :name debe ser evaluada directamente por la aseguradora. ';

        $this->fieldName = [
            'Q' => 'quote_number',
            'I' => 'issue_number',
            'P' => 'policy_number',
        ];

        $this->records = collect([
            'all'             => collect(),
            'all-unread'      => collect(),
            'approved'        => collect(),
            'approved-unread' => collect(),
            'observed'        => collect(),
            'observed-unread' => collect(),
            'rejected'        => collect(),
            'rejected-unread' => collect(),
        ]);
    }


    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }


    /**
     * @param string $code
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function setModel($code)
    {
        try {
            $this->model = app('Sibas\Entities\\' . ucfirst($code) . '\Header');
        } catch (\ReflectionException $e) {
            $this->errors = $e->getMessage();
        }
    }
    
    protected function setModelCollection($code)
    {
        try {
            $this->model = app('Sibas\Entities\\' . ucfirst($code) . '\Collection');
        } catch (\ReflectionException $e) {
            $this->errors = $e->getMessage();
        }
    }


    /**
     * @param string $code
     * @param bool   $cron
     *
     * @return array
     */
    public function getRelations($code, $cron = false)
    {
        switch ($code) {
            case 'de':
                $this->relations = [
                    'details.client.activity',
                    'details.beneficiary',
                    'details.facultative.observations.state',
                    'details.coverages',
                    'coverage',
                    'creditProduct',
                    'placeIssuance',
                    'creditLine',
                    'cancellation.user',
                ];
                break;
            case 'au':
                $this->relations = [
                    'client.activity',
                    'details.vehicleType',
                    'details.vehicleMake',
                    'details.vehicleModel',
                    'details.category',
                ];
                break;
            case 'tj':
                $this->relations = [
                    'details.client.activity',
                    'cancellation.user',
                    'retailerCityAgency.retailerCity.city',
                    'retailerCityAgency.agency',
                ];
                break;
            case 'td':
                $this->relations = [ 'client.activity', 'details', ];
                break;
            case 'vi':
                $this->relations = [ 'details.client.accounts', 'details.beneficiaries', 'client.activity', ];
                break;
            case 'elp':
                $this->relations = [
                    'detail.client.accounts',
                    'detail.client.activity',
                    'detail.reference',
                    'detail.beneficiary',
                    'coverage',
                    'city',
                    'agency',
                    'state',
                    'collections',
                ];
                break;
            case 'eb':
                $this->relations = [
                    'detail.client',
                    'detail.client.activity',
                    'detail.beneficiary',
                    'coverage',
                    'agency',
                    'cashier',
                    'collection',
                ];
                break;
        }

        array_push($this->relations, 'user.city', 'user.agency', 'certificate', 'retailerProduct');

        if ($cron) {
            return $this->relations;
        }
    }
    
    public function getRelationsCollection($code, $cron = false)
    {
        switch ($code) {
            case 'tj':
                $this->relations = [
                    'header.details.client.activity',
                    'header.details.beneficiary',
                    'header.cancellation.user',
                ];
                break;
        }

        array_push($this->relations, 'header.user.city', 'header.user.agency', 'header.certificate', 'header.retailerProduct');

        if ($cron) {
            return $this->relations;
        }
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
    public function getArray()
    {
        return $this->array;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }


    /** Save Model
     *
     * @return bool
     */
    public function saveModel()
    {
        try {
            if ($this->model->save()) {
                return true;
            }
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /** Get next number (Quote - Issue)
     *
     * @param string $field
     *
     * @param int    $default
     *
     * @return int
     */
    public function getNumber($field, $default = 1)
    {
        if($field == 'I') {
            $max = \Illuminate\Support\Facades\DB::table($this->model['table'])
                    ->join('ad_users', $this->model['table'] . '.ad_user_id', '=', 'ad_users.id')
                    ->where('ad_users.ad_city_id', '=', request()->user()->ad_city_id)
                    ->where('type', 'I')
                    ->max($this->fieldName[$field]);
        } else
            $max = $this->model->max($this->fieldName[$field]);

        return is_null($max) ? $default : $max + 1;
    }
    
    /**
     * Numero de emision solo para producto Tarjetas
     * @param type $field
     * @param type $default
     * @return type
     */
    public function getNumberTj($field, $default = 1, $card)
    {
        if($field == 'I') {
            $max = \Illuminate\Support\Facades\DB::table($this->model['table'])
                    ->join('ad_users', $this->model['table'] . '.ad_user_id', '=', 'ad_users.id')
                    ->join('op_tj_details', $this->model['table'] . '.id', '=', 'op_tj_details.op_tj_header_id')
                    ->where('type', 'I')
                    ->where('op_tj_details.card', $card)
                    ->max($this->fieldName[$field]);
        } else
            $max = $this->model->max($this->fieldName[$field]);

        return is_null($max) ? $default : $max + 1;
    }
    
    public function getNumberByPolicy($field, $default = 1, $policy_id)
    {
        if($field == 'I') {
            $max = \Illuminate\Support\Facades\DB::table($this->model['table'])
                    ->join('ad_users', $this->model['table'] . '.ad_user_id', '=', 'ad_users.id')
                    //->where('ad_users.ad_city_id', '=', request()->user()->ad_city_id)
                    ->where('type', 'I')
                    ->where('ad_policy_id', $policy_id)
                    ->max($this->fieldName[$field]);
        } else
            $max = $this->model->max($this->fieldName[$field]);

        return is_null($max) ? $default : $max + 1;
    }
    
    public function getNumberNew($field, $default = 1)
    {
        if($field == 'I') {
            $max = \Illuminate\Support\Facades\DB::table($this->model['table'])
                    ->join('ad_users', $this->model['table'] . '.ad_user_id', '=', 'ad_users.id')
                    ->where('ad_users.ad_city_id', '=', request()->user()->ad_city_id)
                    ->where('type', 'I')
                    ->where('version', 0)
                    ->max($this->fieldName[$field]);
        } else
            $max = $this->model->max($this->fieldName[$field]);

        return is_null($max) ? $default : $max + 1;
    }


    /** Verifies registration number (Quote - Issue)
     *
     * @param string $field
     * @param int    $number
     *
     * @return bool
     */
    public function checkNumber($field, $number)
    {
        return $this->model->where($this->fieldName[$field], $number)->exists();
    }
    
    public function checkNumberTj($field, $number, $card)
    {
        return $this->model->where($this->fieldName[$field], $number)->whereHas('details', function ($q) use($card) {
                $q->where('card', $card);
            })->exists();
    }


    protected function getSelectOption()
    {
        return $this->selectOption;
    }


    /** Returns a data forms select
     *
     * @param array $data
     *
     * @return Collection
     */
    protected function getData($data)
    {
        $d = [];

        foreach ($data as $key => $value) {
            $d[] = [
                'id'   => $key,
                'name' => $value
            ];
        }

        return $this->selectOption->merge($d);
    }


    /**
     * @param double  $rate
     * @param integer $details
     *
     * @return float|int
     */
    public function getRateTendered($rate, $details)
    {
        
        $Fd = [
            1 => 0,
            2 => 0.10,
            3 => 0.12,
            4 => 0.17,
        ];

        $TR = $rate;
        //$n  = $details;
        $n  = ($details > 4)?4:$details;
        $Fn = ( $n > 3 ) ? $Fd[4] : $Fd[$n];
        
        $rate_final = ( $TR * $n ) * ( 1 - $Fn );
        
        return round($rate_final,4);
    }


    /**
     * @param $header_id
     * @param $clients
     *
     * @return bool|void
     */
    public function setClientCacheSP($header_id, $clients)
    {
        $clients = json_encode($clients);
        $key     = 'clients_' . $header_id;

        Cache::put($key, $clients, 60);
    }


    public function destroyClientCacheSP($header_id, $detail_id)
    {
        $key = 'clients_' . $header_id;

        if (Cache::has($key)) {
            $clients = Cache::pull($key);

            if ( ! is_null($clients)) {
                $clients = json_decode($clients, true);
                $client  = array_shift($clients);

                if ($client === $detail_id) {
                    $this->setClientCacheSP($header_id, $clients);
                }

                if (count($clients) > 0) {
                    return true;
                }
            }

        }

        return false;
    }


    public function getAmountInBs($currency, $amount, $bs_value)
    {
        switch ($currency) {
            case 'USD':
                $amount = $amount * $bs_value;
                break;
        }

        return $amount;
    }


    public function getEvaluationResponse($response)
    {
        $questions = json_decode($response->response, true);

        foreach ($questions as $question) {
            if ($question['expected'] != $question['response']) {
                return true;
            }
        }

        return false;
    }


    /**
     * @param MailController $mail
     * @param string         $rp_id
     * @param bool           $response
     *
     * @return bool
     */
    public function sendMail(MailController $mail, $rp_id, $response = false)
    {
        $profiles = '';
        $process  = '';
        $subject  = ':process : Respuesta :response a Caso Facultativo No. ' . $this->header->prefix . '-' . $this->header->issue_number . ' ' . $this->client->full_name;

        $template = 'de.facultative.';

        switch ($this->approved) {
            case 1:
                $process = 'Aprobado';
                $template .= 'process';
                break;
            case 0:
                $process = 'Rechazado';
                $template .= 'process';
                break;
            case 2:
                $process = $this->fa->observations->last()->state->state;

                if ($response) {
                    $template .= 'response';
                    $subject = str_replace(':response', 'del Oficial de Credito', $subject);
                } else {
                    $template .= 'observation';
                }
                break;
        }

        $subject = str_replace(':response', 'de la aseguradora', $subject);
        $subject = str_replace(':process', $process, $subject);

        $mail->subject  = $subject;
        $mail->template = $template;

        if ($this->approved >= 0 && ! $response) {
            array_push($mail->receivers, [
                'email' => $this->header->user->email,
                'name'  => $this->header->user->full_name,
            ]);
        } elseif ($response) {
            array_push($mail->receivers, [
                'email' => $this->fa->observations->last()->user->email,
                'name'  => $this->fa->observations->last()->user->full_name,
            ]);
        }

        $fa     = $this->fa;
        $client = $this->client;

        if ($mail->send(decode($rp_id), compact('fa', 'client'), $profiles)) {
            return true;
        }

        return false;
    }


    /**
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|Header          $header
     *
     * @return bool
     */
    public function getStatusVg($retailerProduct, $header)
    {
        if (! in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ])) {
            $parameter    = $retailerProduct->parameters()->where('slug', 'GE')->first();
            $exchangeRate = $retailerProduct->retailer->exchangeRate;

            if (( $parameter instanceof ProductParameter ) && ( $exchangeRate instanceof ExchangeRate )) {
                $amount_requested = ( $header->currency === 'USD' ) ? $header->amount_requested * $exchangeRate->bs_value : $header->amount_requested;

                if ($amount_requested <= $parameter->amount_max) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * @param Model|RetailerProduct $retailerProduct
     * @param string                $credit_product
     *
     * @return bool
     */
    public function getCertificate($retailerProduct, $credit_product = null)
    {
        $c = $retailerProduct->certificates();

        if (in_array($credit_product, [ 'PMO', 'PLT' ])) {
            $c->whereHas('creditProduct', function ($q) {
                $q->whereIn('slug', [ 'PMO', 'PLT' ]);
            });
        } else {
            $c->whereNull('ad_credit_product_id');
        }

        $this->certificate = $c->where('active', true)->take(1)->first();

        if ($this->certificate instanceof Certificate) {
            return true;
        }

        return false;
    }


    /**
     * @param string                 $date
     * @param Model|ProductParameter $parameter
     *
     * @return bool
     */
    public function checkAgeByParameter($date, $parameter)
    {
        $date = $this->carbon->createFromFormat('d/m/Y', $date);

        if ($date->age >= $parameter->age_min && $date->age <= $parameter->age_max) {
            return true;
        }

        return false;
    }


    /**
     * @param string $slug
     *
     * @return null|Model|User
     */
    public function getCashierBySlug($slug)
    {
        $user = Cashier::where('slug', $slug)->first();

        if ($user instanceof Cashier) {
            return $user;
        }

        return null;
    }


    /**
     * @param        $headers
     * @param string $view
     * @param string $title
     * @param string $type
     * @param string $format
     */
    public function exportExcel($headers, $view, $title, $type = '', $format = '')
    {
        \Debugbar::disable();

        /*$result = view($view, [
            'headers' => $headers,
            'type'    => $type,
            'format'  => $format,
        ])->render();

        header("Content-type: application/vnd.ms-excel charset=utf-8");
        header("Content-Disposition: attachment; filename=Reporte-" . $title . "-" . date('Ymd') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        return $result;*/

        Excel::create('Reporte-' . $title . '-' . date('Ymd'), function ($excel) use ($headers, $view, $type, $format) {
            $excel->setTitle('Reportes');
            $excel->setCreator('Abrenet')->setCompany('Abrenet');
            $excel->setDescription('Reportes');

            $excel->sheet('Reporte', function ($sheet) use ($headers, $view, $type, $format) {

                $sheet->setPageMargin([
                    0.25,
                    0.25,
                    0.25,
                    0.30,
                ]);

                $data_report = [
                    'headers' => $headers,
                    'type'    => $type,
                    'format'  => $format,
                ];

                if ($type === 'OP') {
                    $data_report['credit_lines'] = $headers;
                    unset($data_report['headers']);
                }

                $sheet->loadView($view, $data_report);

                $sheet->freezeFirstRow();
                $sheet->setAutoFilter();
                $sheet->setAutoSize(true);
            });
        })->export('xls');
    }
    
    public function exportExcelCollection($collections, $view, $title, $type = '', $format = '')
    {
        \Debugbar::disable();

        Excel::create('Reporte-' . $title . '-' . date('Ymd'), function ($excel) use ($collections, $view, $type, $format) {
            $excel->setTitle('Reportes');
            $excel->setCreator('Abrenet')->setCompany('Abrenet');
            $excel->setDescription('Reportes');

            $excel->sheet('Reporte', function ($sheet) use ($collections, $view, $type, $format) {

                $sheet->setPageMargin([
                    0.25,
                    0.25,
                    0.25,
                    0.30,
                ]);

                $data_report = [
                    'collections' => $collections,
                    'type'    => $type,
                    'format'  => $format,
                ];

                if ($type === 'OP') {
                    $data_report['credit_lines'] = $collections;
                    unset($data_report['headers']);
                }

                $sheet->loadView($view, $data_report);

                $sheet->freezeFirstRow();
                $sheet->setAutoFilter();
                $sheet->setAutoSize(true);
            });
        })->export('xls');
    }


    /**
     * @return array
     */
    public function emailsForWs()
    {
        return [
            'emontano@sudseguros.com' => 'Ernesto Montaño',
            'mmamani@coboser.com'     => 'Miguel Mamani',
            'jvera@coboser.com'       => 'Juan Pablo Vera',
            'cchalco@coboser.com'     => 'Carlos Chalco',
        ];
    }
    
    public function updateCurrency($currency)
    {
        try {
            $this->model->update([
                'currency' => $currency
            ]);
            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }
        return false;
    }
    
    public function getParamGarantias($header,$retailerProduct)
    {
        $ci     = $header->client->dni.$header->client->extension;
        $prod   = $retailerProduct->companyProduct->product->code;
        $charge = 0;
        
        switch ($prod) {
            case 'au':
                $charge = 950;
                $idG = $header->details->first()->guarantee_id;
                break;
            case 'td':
                $charge = 951;
                $idG = $header->details->first()->nro_folio_real;
                break;
            case 'ema':
                $charge = 952;
                $idG = $header->details->first()->guarantee_id;
                break;
            case 'rma':
                $charge = 953;
                $idG = $header->details->first()->guarantee_id;
                break;
            default:
                break;
        }
        //$idG = ($prod == 'td')?$header->details->first()->nro_folio_real:$header->details->first()->guarantee_id;
        $type = strtoupper($prod);
        $code = $header->client->code;
        $header_id = $header->id;
        
        $version = $header->versionCertificates()->where('ad_city_id',$header->user->city->id)->where('active',true)->first();
        
        $arr ['guaranteeId']                   = (string)"$idG";
        //$arr ["abrenetPolicyGuarantees"]       = "$header_id";
        //$arr ["abrenetPolicyGuarantees"]       = config('base.cert_number.'.$type.'.'.$header->user->city->abbreviation).'-'.str_pad($header->issue_number, 4, 0, STR_PAD_LEFT);
        $arr ["abrenetPolicyGuarantees"]       = $version->cert_number.'-'.str_pad($header->issue_number, 4, 0, STR_PAD_LEFT);
        $arr ["numberCobisGuarantees"]         = "$code";
        $arr ["finalRateGuarantees"]           = $header->total_premium/12;
        $arr ["customerCiGuarantees"]          = (string)"$ci";
        $arr ["operationNumberGuarantees"]     = $header->operation_number;
        $arr ["chargeCodeGuarantees"]          = $charge;
        $arr ["finalRateCurrencyGuarantees"]   = ($header->currency=='BS')?1:2;
        $arr ["productTypeGuarantees"]         = (string)"$type";
        $arr ["dateOfIssue"]                   = date('Y-m-d');
        
        return $arr;
    }
    
    public function getParamDe($header, $retailerProduct) 
    {
        $hipotecario = (in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ]))?true:false;
        $rate = 0;
        $ep_rate = 0;
        foreach ($header->details as $key => $value) {
            if($value->approved){
               if($value->facultative != null && $value->facultative->accidental_death == true){
                    $rate+=$value->facultative->current_rate; 
               }else
                    $rate+=$value->rate; 
            }                
            
            if($value->facultative != null && $value->facultative->surcharge == true){
                //$ep_rate+=($value->facultative->current_rate*$value->facultative->percentage)/100;
                $ep_rate+=$value->facultative->final_rate;
            }
                
        }
        $ci = $header->details->first()->client->dni . $header->details->first()->client->extension;
        
        $charge_code = 0;
        if($hipotecario == false){
            $charge_code = 188;
        }else{
            $details = $header->details->where('approved', true)->count();
            if($details == 1)
                $charge_code = 190;
            elseif($details == 2)
                $charge_code = 191;
            elseif($details == 3)
                $charge_code = 192;
            elseif($details > 3)
                $charge_code = 193;
        }
        
        return [
            'abrenet_policy'        => $header->id,
            'number_cobis'          => $header->details->first()->client->code,
            'operation_number'      => $header->operation_number,
            'final_rate'            => $rate*12,
            'customer_ci'           => (string)"$ci",
            'charge_code'           => $charge_code,
            'final_rate_currency'   => ($header->currency=='BS')?1:2,
            'ep_final_rate'         => ($ep_rate>0)?($ep_rate*12):'',
            'ep_charge_code'        => ($ep_rate>0)?(($hipotecario)?194:189):'',
            'ep_currency'           => ($ep_rate>0)?(($header->currency=='BS')?1:2):'',
            #'date_of_issue'         => date('Y-m-d'), // se deja de enviar a solicitud de Melany de FOrtaleza
        ];
    }
    
    public function getParamCesantia($header, $retailerProduct) 
    {
        $rate = 0;
        foreach ($header->details as $key => $value) {
            if($value->approved && $value->cesantia)
                $rate+= $value->rate_cesantia;
        }

        $client = $header->details->first()->client;
        return [
            'abrenet_policy'        => $header->details->first()->id,
            'number_cobis'          => $client->code,
            'operation_number'      => $header->operation_number,
            'final_rate'            => $rate*12,
            'customer_ci'           => str_replace(' ','',$client->full_dni),
            'charge_code'           => 180,
            'final_rate_currency'   => ($header->currency=='BS')?1:2,
            'ep_final_rate'         => '',
            'ep_charge_code'        => '',
            'ep_currency'           => '',
            'date_of_issue'         => date('Y-m-d'),
        ];
    }

    /**
     * Genera archivo pdf
     */
    public function exportPdf($headers, $maxHeaders, $view, $title, $format, $retailerProduct)
    {
        $pdf = new \Sibas\Http\Controllers\PdfController();
        
        $payload = view($view, [
                                'headers' => $headers,
                                'maxHeaders' => $maxHeaders,
                                'format'  => $format,
                                'user'  => request()->user(),
                                'retailerProduct'  => $retailerProduct,
                            ]);
        return $pdf->create('<div style="width: 725px; font-weight: normal; font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">'.$payload->render().'</div>', 'Reporte Gerencial-'.date('d-m-Y'));
    }

    public function createXlsByArray($title, $data)
    {
        return Excel::create(str_replace(' ' , '-', strtolower($title)), function($excel) use ($data) {
            
            foreach ($data as $key => $value){
                $excel->sheet($key, function ($sheet) use ($value) {
                    $sheet->setOrientation('landscape');
                    $sheet->fromArray($value);
                });
            }
        })->store('xlsx', storage_path('exports/attachments'), true);
    }

    public function sendEmail($title, $body, $file_xls=[], $file=false, $emailsTo, $emailsCc)
    {
        $users  = $emailsTo;
        
        $emails = $emailsCc;
        
        return \Illuminate\Support\Facades\Mail::raw($title, function ($message) use ($file_xls, $file, $title, $users, $emails, $body) {
            $message->from(env('MAIL_USERNAME'), env('MAIL_NAME'));
            $message->subject($title);

            foreach ($users as $email => $full_name) {
                $message->to($email, $full_name);
            }

            foreach ($emails as $email => $name) {
                $message->cc($email, $name);
            }
            if($body)
                $message->setBody($body, 'text/html');;
            
            if(count($file_xls)>0){
                foreach ($file_xls as $key => $value){
                    $message->attach($value['full']);
                }
            }
            if($file){
                $message->attach($file['full']);
            }
                
        });
        
    }

    public function createCsvByArray($file_name, $data)
    {
        $path = storage_path('exports/attachments').'/'.$file_name.'.csv';
        $handle = fopen($path, 'w');

        var_dump('total: '.count($data));

        fputcsv($handle, array_keys($data[0]));
        foreach ($data as $key => $row) {
            var_dump(($key+1));
            fputcsv($handle, $row);
        }
        fclose($handle);

        if(file_exists($path))
            return $path;
        else
            return false;
    }

    public function compressZipFile($file_name, $path)
    {
        $zipper = new Zipper;

        $zipper->make(storage_path('exports/attachments/') . $file_name . '.zip')->add($path)->close();
        
        if(file_exists(storage_path('exports/attachments/') . $file_name . '.zip'))
            return storage_path('exports/attachments/') . $file_name . '.zip';
        else
            return false;
    }

    public function loadFile($file)
    {
        if(file_exists(storage_path('exports/'.$file))){
            $this->array = [];
            Excel::load(storage_path('exports/'.$file), function($reader) {
                $results = $reader->get();
                $this->array = $results->toArray(); 
            });
    
            return $this->array;
        }

        return false;
    }

    public function sendExcelAttached($title, $array, $emailsTo, $emailsCc=[])
    {
        $file1 = $this->createXlsByArray(uniqid(), [$title => $array]);

        if($file1){
            if($this->sendEmail($title, false, [$file1], false, $emailsTo, $emailsCc)){
                unlink($file1['full']);
                $this->result = 'email sent';
                return true;
            }else{
                $this->errors = 'unsent mail';
                return false;
            } 
        }else
            $this->errors = 'the file cannot be created';

        return false;
    }

    public function storageStoreFile($request, $input_id)
    {
        if($request->hasFile($input_id)){
            $file = $request->file($input_id);
            if (in_array(strtolower($file->getClientOriginalExtension()), setting('api.file_upload'))) {
                $file_name = 'file_'.date('U').'.'.$file->getClientOriginalExtension();
                    if($file->move(storage_path('exports'), $file_name)){
                        chmod(storage_path('exports/'.$file_name), 0777);
                        $this->result = $file_name;
                        return true; 
                    }else
                        $this->errors = 'store file error';
            }else
                $this->errors = 'extension error';
        }else
            $this->errors = $input_id.' required';

        return false;
    }

    public function getDataFile($request, $input_id)
    {
        if($request->hasFile($input_id)){
            $file = $request->file($input_id);
            if (in_array(strtolower($file->getClientOriginalExtension()), setting('api.file_upload'))) {
                $this->result = [];
                Excel::load($file, function($reader) {
                    $results = $reader->get();
                    $this->result = $results->toArray(); 
                });
                return true;
            }else
                $this->errors = 'extension error';
        }else
            $this->errors = $input_id.' required';

        return false;
    }

    public function get_data($request)
    {
        $data = $request->all();
        $model = DB::table($data['table']);
                
        foreach ($data['condition'] as $key => $value) {
            $model->where($key,$value);
        }
        return $model->get();

    }

    public function attached($title, $data)
    {
        if($this->sendExcelAttached($title, json_decode(json_encode($data), true), setting('emailTo')))
                $arr = response()->json(['message' => 'success', 'file_name'=>$this->getResult()], 200);
            else
                $arr = response()->json(['error' => $this->getErrors()], 401);

        return $arr;
    }

    public function configuration()
    {
        
        $envFilePath = base_path('.env');
        $variables = file_get_contents($envFilePath);

        dd($variables);
        return $variables;
    }

    public function executeCall($request)
    {
        $info = $request->all();
        if(isset($info['params']) && $info['params'])
            Artisan::call($info['call'],$info['params']);
        else
            Artisan::call($info['call']);
        
        return ['ressponse'=>'executed command'];
    }

    public function uploadFile($request)
    {
        if($this->storageStoreFile($request, 'file'))
            return ['message' => 'success', 'file_name'=>$this->getResult()];
        else
            return ['error' => $this->getErrors()];
    }

    public function executeQuery($request)
    {
        if(DB::statement($request['query']))
            return ['message' => 'success', 'state'=>'executed correctly'];
        else
            return ['error' => 'error executed'];
    }

    /**
     * DESDE AQUI SE ACTUALIZO LAS FUNCIONES
     * @param Model|RetailerProduct $retailerProduct
     * @param string                $credit_product
     *
     * @return bool
     */
    public function getNumberPrefix($prefix,$field,$city='',$default = 1)
    {
        $max = $this->model->where('prefix', '=', $prefix)->max($this->fieldName[$field]);

        //dd($max);
        return is_null($max) ? $default : $max + 1;
    }

    public function getPolicyNumber($issue_number, $code, $city = '', $default = 1)
    {
        switch ($code){
            case 'VI':
                $policy = 'V180-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VM':
                //$policy = 'VMAX-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                $policy = 0 + $issue_number;
                break;
            case 'VPC':
                $policy = 'VMPC-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VP':
                $policy = 'VPRM-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VS':
                $policy = 'VPLU-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VC':
                $policy = 'COBE-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'SC':
                $policy = '6641'.str_pad($issue_number, 6, "0", STR_PAD_LEFT);
                break;
            case 'SCP':
                $policy = 'VSPL-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'PC':
                $policy = 'CBE2-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VITC':
                $policy = '18TC-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VMTC':
                $policy = 'MXTC-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VPTC':
                $policy = 'PRTC-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VSTC':
                $policy = 'PLTC-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'VCTC':
                $policy = 'COTC-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'PCTC':
                $policy = 'CBTC-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'SCTC':
                $policy = 6641000000 + $issue_number;
                break;
            case 'SP':
                $policy = 'SEPL-'.str_pad($issue_number, 6, "0", STR_PAD_LEFT).'-00';
                break;
            case 'AP':
                $policy = 0 + $issue_number;
                break;
            case 'SEP':
                $policy = 554168087 + $issue_number;
                break;
            case 'IN':
                $policy = 'IAE-' . $city . '-' . str_pad($issue_number, 5, "0", STR_PAD_LEFT) . '-00-' . date('Y');
                break;
            case 'PR':
                $policy = 7765 + $issue_number;
                break;
            case 'VD':
                $policy = 0 + $issue_number;
                break;
        }

        return is_null($policy) ? $default : $policy;
    }

    /**
     * @param string                 $date
     * @param Model|ProductParameter $parameter
     *
     * @return bool
     */
    public function checkQuantityProducts($details, $code, $dni, $ext, $plan = '')
    {
        switch ($code){
            case 'vi':
                $c = 11;
                break;
            case 'vm':
                $c = 19;
                break;
            case 'vpc':
                $c = 19;
                break;
            case 'vp':
                $c = 2;
                break;
            case 'vs':
                $c = 11;
                break;
            case 'vc':
                $c = 1;
                break;
            case 'pc':
                $c = 1;
                break;
            case 'sc':
                $c = 10;
                break;
            case 'scp':
                $c = 10;
                break;
            case 'ap':
                if ($plan == 'Plan A')
                    $c = 5;
                elseif ($plan == 'Plan B')
                    $c = 3;
                elseif ($plan == 'Plan C')
                    $c = 2;
                elseif ($plan == 'Plan D')
                    $c = 1;
                elseif ($plan == 'Plan F')
                    $c = 1;

                break;
            case 'sep':
                $c = 14;
                break;
            case 'sp':
                $c = 4;
                break;
            case 'in':
                $c = 20;
                break;
            case 'pr':
                $c = 20;
                break;
            case 'vd':
                if ($plan == 'Plan A')
                    $c = 5;
                elseif ($plan == 'Plan B')
                    $c = 3;
                elseif ($plan == 'Plan C')
                    $c = 2;
                elseif ($plan == 'Plan D')
                    $c = 1;
                elseif ($plan == 'Plan V')
                    $c = 1;

                break;
        }

        return $this->checkProduct($details, $dni, $ext, $c);
    }

    public function checkProduct($details, $dni, $ext, $c)
    {
        $i = 0;
        foreach ($details as $key => $value){
            $titular = $value->header->details->first();
            if($titular->issuedheader == true && $titular->header->canceled == false &&
                $titular->client->dni == $dni && $titular->client->extension == $ext &&
                date('Y-m-d') <= date("Y-m-d",strtotime($value->header->date_issue."+ ".$value->header->term." month")))
                $i++;
        }

        if ($i < $c)
            return true;
        else
            return false;
    }
}
