<?php

namespace Sibas\Http\Controllers\API;

use Carbon\Carbon;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\API\GeneralRepository;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\WsRepository;

class CreditCardController extends Controller
{
    /**
     * @var GeneralRepository
     */
    protected $generals;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var WsRepository
     */
    protected $ws;


    public function __construct(
        GeneralRepository $generals,
        ClientRepository $clientRepository,
        WsRepository $ws
        )
    {
        $this->generals = $generals;
        $this->clientRepository = $clientRepository;
        $this->ws = $ws;
        $this->header = [];
        $this->detail = [];
        $this->beneficiary = [];
        
        $this->toDay = Carbon::now();

    }

    public function dataMigrate(Request $request)
    {
        ini_set('memory_limit', '4G');
        set_time_limit(0);
        
        if($this->generals->getDataFile($request, 'file')){
            $data = $this->generals->getResult();
            $data = $data[0];
            
            foreach ($data as $key => $value) {
                
                $data[$key]['issue_number']='';
                $data[$key]['observation']='error: issued';
                
                if($this->client = DB::table('op_clients')->where('code',$value['cage'])->first()){
                    goto issue;
                }else{
                    if(is_null($value['first_name'])){
                        $data[$key]['observation']='error: falta de informacion';
                        goto final_process;
                    }else{
                        if($this->setClient($value)){
                            goto issue;
                        }else{
                            $data[$key]['observation']='error: NO se puedo registrar al cliente';
                            goto final_process;
                        }
                    }       
                }

                issue:
                if($this->issued($key, $value)){
                    $data[$key]['issue_number']=$this->header->issue_number;
                    $data[$key]['observation']='issued';
                }else
                    $data[$key]['observation']='error: emision';

                final_process:
            }
            
            if($this->generals->sendExcelAttached('migrationTj', $data, setting('emailTo')))
                return response()->json(['message' => 'success', 'file_name'=>$this->generals->getResult()], 200);
            else
                return response()->json(['error' => $this->generals->getErrors()], 401);

        }else
            return response()->json(['error' => $this->generals->getErrors()], 401);
    }

    public function setClient($value)
    {
        if(!$this->client = DB::table('op_clients')->where('dni',trim($value['dni']))->first()){
            $birthdate = Carbon::parse($value['birthdate']);
            $age = $this->toDay->diffInYears($birthdate);
            
            $data = [
                'id'=>DB::table('op_clients')->max('id')+1,
                'ad_retailer_id'=>1,
                'code'=>$value['cage'],
                'type'=>'N',
                'first_name'=>trim($value['first_name']),
                'last_name'=>trim($value['last_name']),
                'married_name'=>trim($value['married_name']),
                'birthdate'=>$birthdate->format('Y-m-d'),
                'age'=>$age,
                'birth_place'=>trim($value['ciudad']),
                'dni'=>trim($value['dni']),
                'extension'=>trim($value['extension']),
                'document_type'=>'CI',
                'document_type_bank'=>1,
                'civil_status'=>trim($value['civil_status']),
                'gender'=>'M',
                'locality'=>trim($value['ciudad']),
                'home_address'=>trim($value['home_address']),
                'business_address'=>trim($value['home_address']),
                'ad_activity_id'=>(trim($value['ad_activity_id']))?trim($value['ad_activity_id']):9,
                'occupation_description'=>trim($value['occupation_description']),
                'created_at'=>$this->toDay->format('Y-m-d H:i:s'),
                'updated_at'=>$this->toDay->format('Y-m-d H:i:s'),
            ];

            try {
                DB::table('op_clients')->insert($data);
            } catch (\Exception $e) {
                dd("Error al insertar datos: " . $e->getMessage());
            }
            
            $this->client = DB::table('op_clients')->where('id',trim($data['id']))->first();
        }

        if($this->client)
            return true;

        return false;
    }

    public function issued($key, $value)
    {
        if($this->setHeader($key, $value)){
            if($this->setDetail($value))
                if($this->setBeneficiary($value))
                    return true;
        }
        return false;
    }

    public function setBeneficiary($value)
    {
        if(!$this->beneficiary = DB::table('op_tj_beneficiaries')->where('op_tj_detail_id', $this->detail->id)->first()){
            $data = [
                'id'=>DB::table('op_tj_beneficiaries')->max('id')+1,
                'op_tj_detail_id'=>$this->detail->id,
                'coverage'=>'VI',
                'first_name'=>$this->client->first_name,
                'last_name'=>$this->client->last_name,
                'percentage'=>100,
                'relationship'=>'OTROS',
                'created_at'=>$this->toDay->format('Y-m-d H:i:s'),
                'updated_at'=>$this->toDay->format('Y-m-d H:i:s'),
            ];
            try {
                DB::table('op_tj_beneficiaries')->insert($data);
            } catch (\Exception $e) {
                dd("Error al insertar datos: " . $e->getMessage());
            }
            $this->beneficiary = DB::table('op_tj_beneficiaries')->where('op_tj_detail_id', $this->detail->id)->first();
        }

        if($this->beneficiary)
            return true;

        return false;
    }

    public function setDetail($value)
    {
        if(!$this->detail = DB::table('op_tj_details')->where('op_tj_header_id',$this->header->id)->first()){
            $end_date = Carbon::parse($value['vencimiento']);
            $data = [
                'id'=>DB::table('op_tj_details')->max('id')+1,
                'op_tj_header_id'=>$this->header->id,
                'op_client_id'=>$this->client->id,
                'account_number'=>trim($value['nro_cuenta']),
                'card_number'=>str_replace('*','X',trim($value['numero_tarjeta'])),
                'card'=>'CC',
                'card_customer_id'=>trim($value['cage']),
                'card_account_number'=>trim($value['nro_cuenta']),
                'card_customer_name'=>trim($value['nombre_completo']),
                'card_due_date'=>$end_date->format('dmY'),
                'card_customer_type'=>'T',
                'card_product_type'=>'CLASICA INTERNACIONAL',
                'premium'=>14,
                'holder'=>true,
                'created_at'=>$this->toDay->format('Y-m-d H:i:s'),
                'updated_at'=>$this->toDay->format('Y-m-d H:i:s'),
            ];

            try {
                DB::table('op_tj_details')->insert($data);
            } catch (\Exception $e) {
                dd("Error al insertar datos: " . $e->getMessage());
            }
            $this->detail = DB::table('op_tj_details')->where('op_tj_header_id',$this->header->id)->first();
        }

        if($this->detail)
            return true;

        return false;
    }

    public function setHeader($key, $value)
    {
        $end_date = Carbon::parse(trim($value['vencimiento']));
        $date_issue = Carbon::parse($end_date)->subMonths(60);

        if(!$this->header = DB::table('op_tj_headers')->where('bill_name',trim($value['nombre_completo']))->where('bill_dni',trim($value['dni']))->where('date_issue',$date_issue->format('Y-m-d H:i:s'))->first()){    
            $data = [
                'id'=>DB::table('op_tj_headers')->max('id')+1,
                'ad_user_id'=>1508335865,
                'ad_retailer_product_id'=>1487341817,
                'ad_retailer_city_agency_id'=>10,
                'type'=>'I',
                'policy_number'=>'TDC-LP0201-8-1',
                'issue_number'=>DB::table('op_tj_headers')->max('issue_number')+1,
                'quote_number'=>DB::table('op_tj_headers')->max('quote_number')+1,
                'prefix'=>'TJ',
                'bill_name'=>trim($value['nombre_completo']),
                'bill_dni'=>trim($value['dni']),
                'total_premium'=>14,
                'currency'=>'BS',
                'payment_method'=>'CO',
                'period'=>'M',
                'issued'=>true,
                'date_issue'=>$date_issue->format('Y-m-d H:i:s'),
                'ad_certificate_id'=>46,
                'warranty'=>1,
                'validity_start'=>$date_issue->format('Y-m-d H:i:s'),
                'validity_end'=>$end_date->format('Y-m-d H:i:s'),
                'created_at'=>$this->toDay->format('Y-m-d H:i:s'),
                'updated_at'=>$this->toDay->format('Y-m-d H:i:s'),
            ];

            try {
                DB::table('op_tj_headers')->insert($data);
            } catch (\Exception $e) {
                dd("Error al insertar datos: " . $e->getMessage());
            }

            $this->header = DB::table('op_tj_headers')->where('id',$data['id'])->first();
        }

        if($this->header)
            return true;
        
        return false;
    }
}