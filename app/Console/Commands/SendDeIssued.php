<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Entities\De\Header;
use Sibas\Repositories\Report\ReportGeneralRepository;
use Sibas\Repositories\De\HeaderRepository;

class SendDeIssued extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_email_de_issued';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia reporte de todos los registros emitidos en FORTALEZA un dia antes';    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $reportGeneralRepository;
      /**
     * @var HeaderRepository
     */
    protected $headerRepository;
    /**
     * Create a new command instance.
     *
     * @param ReportGeneralRepository   $reportGeneralRepository
     */
    public function __construct(
        HeaderRepository $headerRepository,
        ReportGeneralRepository $reportGeneralRepository
        
    )
    {
        parent::__construct();
        $this->headerRepository             = $headerRepository;
        $this->reportGeneralRepository   = $reportGeneralRepository;      
    }
     /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {    
      ini_set('memory_limit', -1);
      set_time_limit(0);
      //extraer todos los headers emitidos hasta un dia antes de la consulta
      $date_prev = Carbon::now()->subDay();  
      //$start_date = Carbon::createFromDate(2023, 1, 1)->startOfMonth();
      //$end_date = Carbon::createFromDate(2023, 1, 1)->endOfMonth();
        
      $headers = DB::table('op_de_headers')
      ->select(
        DB::raw("CONCAT_WS('',SUBSTRING_INDEX(op_de_headers.policy_number, '-', 1),'-', op_de_headers.issue_number, op_de_headers.version_number ) AS 'NRO DE CERTIFICADO'"),
        'ad_coverages.name AS COBERTURA',
        DB::raw("CONCAT_WS('', op_de_details.percentage_credit, ' % ') AS 'PARTICIPACIÓN'" ),  
        'ad_credit_products.name AS PRODUCTO',
        DB::raw("CONCAT_WS('-', op_clients.first_name, op_clients.last_name) AS CLIENTE"),
        DB::raw("CONCAT_WS(' ', op_clients.dni, op_clients.extension) AS CI"),
        DB::raw("CONCAT_WS(' ', op_de_headers.amount_requested, op_de_headers.currency) AS 'MONTO SOLICITADO / MONEDA'"),
        DB::raw("CONCAT_WS(' ',IF(op_de_details.balance = 0, '0', op_de_details.balance),'BS') AS 'SALDO DEUDOR'"),
        DB::raw("CONCAT_WS(' ',op_de_details.cumulus,'BS') AS 'TOTAL MONTO ACUMULADO'"),      
        DB::raw("CONCAT_WS(' ', op_de_headers.term, IF(op_de_headers.type_term = 'Y','Años', IF (op_de_headers.type_term = 'M','Meses',IF (op_de_headers.type_term = 'W','Semanas','Dias' ) )) )AS 'PLAZO DEL CREDITO / TIPO DEL PLAZO'"),
        DB::raw("IF(op_de_details.headline = 'D', 'Deudor', 'Codeudor') AS TITULAR"),
        'ad_users.full_name AS USUARIO',
        DB::raw("CONCAT_WS('/',ad_cities.name, ad_agencies.name) AS 'SUCURSAL / AGENCIA '"),
        DB::raw("op_de_headers.created_at AS 'FECHA DE INGRESO'"),
        DB::raw("IF(ISNULL (op_de_headers.type_of_request) , 'Sin información',IF(op_de_headers.type_of_request='N-OPE','Nueva Operación',IF(op_de_headers.type_of_request='REF','Refinanciamiento',IF(op_de_headers.type_of_request='REP','Reprogramación',IF(op_de_headers.type_of_request='R-EXC','Regularización de Exclusión',NULL))))) AS 'TIPO DE SOLICITUD'"),
        DB::raw("IF (op_de_facultatives.state='PR' && op_de_facultatives.accidental_death=1, 'MUERTE ACCIDENTAL',
                (SELECT GROUP_CONCAT(
                    CASE coverage
                        WHEN 'CF' THEN ''
                        WHEN 'CTPD' THEN '*Invalidez Total Permanente'
                        WHEN 'CD' THEN '*Muerte'
                    END SEPARATOR ''
                ) AS 'COBERTURAS'
                FROM op_de_detail_coverages
                WHERE op_de_detail_id = op_de_details.id
                AND granted = 1
                ))AS 'COBERTURAS'"),
        DB::raw("IF(op_de_headers.issued = 1, 'SI', 'NO EMITIDO') AS EMITIDO"),
        DB::raw("op_de_headers.date_issue AS 'FECHA DE EMISIÓN'"),    
        DB::raw("IF(op_de_headers.issued =1 && op_de_headers.facultative=0, 'Aprobado Free Cover', IF(op_de_facultatives.state='PR' && op_de_facultatives.approved = 1 , 'APROBADO',  'RECHAZADO')) AS 'ESTADO COMPAÑIA'"),
        DB::raw("IF(op_de_headers.facultative=0,' ',IF(op_de_facultatives.state='PR' && op_de_facultatives.approved = 1 , 'APROBADO', 'RECHAZADO')) AS 'MOTIVO ESTADO COMPAÑIA'"),
        DB::raw("IF(op_de_headers.issued = 1, 'EMITIDO', 'NO EMITIDO') AS 'ESTADO BANCO'"),
        DB::raw("IF(op_de_headers.facultative=0,' ',op_de_facultatives.observation) AS 'FACULTATIVO OBSERVACIÓN'"),
        DB::raw("op_de_headers.date_issue AS 'FECHA RESPUESTA FINAL COMPAÑIA'"),     
        DB::raw("IF(op_de_headers.issued =1 && op_de_headers.facultative=1 && op_de_facultatives.state='PR',IF(DATEDIFF (op_de_facultatives.updated_at,op_de_headers.created_at)=0,'0',DATEDIFF (op_de_facultatives.updated_at,op_de_headers.created_at)),IF(DATEDIFF(op_de_headers.date_issue, op_de_headers.created_at)=0,'0',DATEDIFF(op_de_headers.date_issue, op_de_headers.created_at))) AS 'DIAS EN PROCESO'"),
        DB::raw("CONCAT_WS(' ',IF(DATEDIFF(op_de_headers.date_issue, op_de_headers.created_at)=0,'0',DATEDIFF(op_de_headers.date_issue, op_de_headers.created_at)) ,'Dias') AS 'DURACION TOTAL DEL CASO'"),
        DB::raw("CONCAT_WS(' ',IF(op_de_facultatives.state = 'PR' AND op_de_facultatives.approved = 1 AND op_de_facultatives.surcharge = 1, op_de_facultatives.percentage, '0') ,'%') AS 'PORCENTAJE EXTRAPRIMA'"),
        DB::raw("CONCAT_WS(' ',IF(op_de_facultatives.state='PR' && op_de_facultatives.approved = 1, op_de_facultatives.percentage_bank, '0') ,'%' ) AS 'PORCENTAJE EXTRAPRIMA BANCO'"),
        DB::raw("CONCAT_WS(' ',IF(op_de_facultatives.state='PR' && op_de_facultatives.approved = 1, op_de_facultatives.percentage_cia, '0') ,'%' ) AS 'PORCENTAJE EXTRAPRIMA CIA'"),
        DB::raw("IF(op_de_facultatives.state='PR' && op_de_facultatives.approved = 1, op_de_facultatives.current_rate, op_de_details.rate) AS 'TASA ACTUAL'"),
        DB::raw("IF(op_de_facultatives.state='PR' && op_de_facultatives.approved=1 && op_de_facultatives.surcharge=1, op_de_facultatives.final_rate, '0.0000') AS 'TASA RECARGO'"),
        DB::raw("IF(op_de_facultatives.state='PR', op_de_facultatives.monetary_pricing, '') AS 'TARIFICACION MONETARIA'"),
        DB::raw("IF(ad_credit_products.slug='PCA',op_de_headers.card_number,op_de_headers.operation_number ) AS 'NUMERO DE OPERACION'"),
        DB::raw("IF(op_de_details.cesantia =1 , 'SI','NO') AS 'COBERTURA DE DESEMPLEO'"),
        DB::raw("IF(op_de_details.rate_cesantia=0,'0',op_de_details.rate_cesantia) AS 'TASA DESEMPLEO'"),
        DB::raw("IF(op_de_headers.canceled = 1, 'SI', 'NO') AS ANULADO"),
        DB::raw("IF(op_de_headers.canceled = 0, '-', (SELECT full_name FROM ad_users WHERE id = op_de_cancellations.ad_user_id)) AS 'ANULADO POR'"),
        DB::raw("IF(op_de_headers.canceled = 0, '-', op_de_cancellations.reason) AS 'MOTIVO DE ANULACIÓN'"),
        DB::raw("IF(op_de_headers.canceled = 0, '-', op_de_cancellations.created_at) AS 'FECHA DE ANULACIÓN'"))
     ->join('op_de_details', 'op_de_headers.id', '=', 'op_de_details.op_de_header_id')
     ->leftJoin('ad_coverages', 'op_de_headers.ad_coverage_id', '=', 'ad_coverages.id')
     ->leftJoin('ad_credit_products', 'op_de_headers.ad_credit_product_id', '=', 'ad_credit_products.id')
     ->join('op_clients', 'op_de_details.op_client_id', '=', 'op_clients.id')
     ->join('ad_users', 'op_de_headers.ad_user_id', '=', 'ad_users.id')
     ->leftJoin('op_de_facultatives', 'op_de_details.id', '=', 'op_de_facultatives.op_de_detail_id')
     ->join('ad_retailer_city_agencies', 'op_de_headers.ad_retailer_city_agency_id', '=', 'ad_retailer_city_agencies.id')
     ->join('ad_retailer_cities', 'ad_retailer_city_agencies.ad_retailer_city_id', '=', 'ad_retailer_cities.id')
     ->join('ad_agencies', 'ad_retailer_city_agencies.id', '=', 'ad_agencies.id')
     ->join('ad_cities', 'ad_retailer_cities.id', '=', 'ad_cities.id')
     ->leftJoin('op_de_detail_coverages', 'op_de_details.id', '=', 'op_de_detail_coverages.op_de_detail_id')
     ->leftJoin('op_de_cancellations','op_de_headers.id','=','op_de_cancellations.op_de_header_id')
     //->whereBetween('date_issue', [$start_date, $end_date])
     ->whereDate('date_issue', '<=', $date_prev->copy()->endOfDay())
     ->where('op_de_headers.issued', 1)
     ->where('op_de_headers.type', 'I')
     ->groupBy('op_de_details.id')
     ->orderBy('issue_number', 'desc') 
     //->limit(50)
     ->get();

      //armar el archivo excel con la vista de report desgravamen
      $date_prev = json_decode(json_encode($date_prev), true);
      $fecha_prev= date('d-m-Y', strtotime($date_prev['date']));
      

      $title          = 'Reporte Polizas Emitidas Desgravamen hasta '.$fecha_prev;
      $fileName       = str_replace(' ', '-', $title);
      $emailsTo       = ['edwinm@tubroker.com'=>'Edwin Manuel'];
      $emailsCc       = [
            'mgarcia@fortaleza.com.bo' => 'Max Garcia',
            'fortalezascz@tubroker.com' => 'Ana Paola Lino Cruz',
        ];
        
      $file1= $this->headerRepository->createCsvByArray($fileName, json_decode(json_encode($headers), true));
        
      $message ='';
      if($file1){
            $zip['full'] = $this->headerRepository->compressZipFile($fileName, $file1);
            if($this->headerRepository->sendEmail($title, false, [], $zip, $emailsTo, $emailsCc)){
                unlink($file1);
                unlink($zip['full']);
                $message = 'Correo enviado';
            }else
                $message = 'Correo no enviado';
      }
      dd($message);   
    }
}
