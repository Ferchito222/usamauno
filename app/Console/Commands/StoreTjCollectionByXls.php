<?php

namespace Sibas\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Repositories\Tj\CollectionRepository;
use Sibas\Repositories\Tj\HeaderRepository;

class StoreTjCollectionByXls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store_collections_by_xls {file_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza el registro de cobros Tarjetas de debito desde el documento xls';
    
    /**
     *@var CollectionRepository
     */
    protected $collectionRepository;
    
    /**
     *@var HeaderRepository
     */
    protected $headerRepository;
    
    public $array;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
            CollectionRepository $collectionRepository,
            HeaderRepository $headerRepository
            )
    {
        parent::__construct();
        $this->collectionRepository = $collectionRepository;
        $this->headerRepository = $headerRepository;
        $this->array = [];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        #archivos aceptados con extension "xlsx"
        if($this->argument('file_name')){
            
            if($this->storeCollectionsByXls($this->argument('file_name'))){
                var_dump('correcto - '.$this->argument('file_name'));
            }else{
                var_dump('erroneo - '.$this->argument('file_name'));
            }
        }else{
            echo 'error key list files';
        }
    }

    public function storeCollectionsByXls($file)
    {   ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        
        Excel::load(storage_path('exports/'.$file), function($reader) {
            $results = $reader->get();
            $this->array = $results->toArray(); 
        });

        $data = $this->array[0];
        $lot_id = date('Ymd');
        $errors = [];
        $all = [];
        foreach ($data as $key => $value) {
            
            $err = '';
            $ok = false;
            if($this->headerRepository->getHeaderByIssueNumber($value['certificatenumber'])){
                $header = $this->headerRepository->getModel();
                
                if(!$this->collectionRepository->collectionsByTransactionNumber($value['nro_transaccion'])->count()){
                    $collections = $this->collectionRepository->collectionsByHeaderNotCharged($header->id);
                    if($collections->count()>0){
                        $data = [
                            'charged'=>true,
                            'transaction_number' => $value['nro_transaccion'],
                            'transaction_date' => $value['fecha_transaccion'],
                            'transaction_amount' => $value['importe_debitado'],
                            'currency_fortaleza' => true,
                            'send_fortaleza' => true,
                            'cardNumber' => $value['cardnumber'],
                            'customerName' => $value['nombre_cliente'],
                            'accountNumber' => $value['nro_cuenta'],
                            'transactionDate' => $value['fecha_transaccion'],
                            'observation' => $value['obs'],
                            'paymentStatus' => '0',
                        ];
                        if($collections->first()->update($data)){
                            $ok = true;
                        }else
                            $err='Erro al actualizar registro de cobro';
                    }else
                        $err='NO cuenta con cobros pendientes';

                }else
                    $err = 'El nro de transaccion ya fue registrado anteriormente';
            }else
                $err = 'No existe nro de emision';
            $all[$key]=$value;
            $all[$key]['registrado en abrenet'] = ($ok)?'SI':'NO';
            $all[$key]['observacion'] = ($ok)?'Registrado Correctamente':$err;
        }
        
            $date           = date("d/m/Y");
            $id             = date('U'); 
            $title          = 'Migracion '.str_replace('/', '-', $date);
            $fileName       = 'migracion';
            $emailsTo       = ['EdwinM@tubroker.com'=>'Edwin Manuel'];
            $emailsCc       = [];

            $file1 = $this->collectionRepository->createXlsByArray($fileName, [$fileName => $all]);

            if($file1){
                if($this->collectionRepository->sendEmail($title, false, [$file1], false, $emailsTo, $emailsCc)){
                    unlink($file1['full']);
                    echo 'Correo enviado';
                    return true;
                }else
                    echo 'Correo no enviado';
            }
        
        return false;
    }
    
}
