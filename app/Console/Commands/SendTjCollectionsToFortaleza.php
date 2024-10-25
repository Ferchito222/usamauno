<?php

namespace Sibas\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Sibas\Repositories\Tj\CollectionRepository;
use Sibas\Repositories\Tj\CancellationRepository;
use Sibas\Repositories\WsFortalezaRepository;

class SendTjCollectionsToFortaleza extends Command
{
    /**
     * @var CollectionRepository
     */
    protected $collectionRepository;

    /**
     * @var CancellationRepository
     */
    protected $cancellationRepository;

    /**
     * @var WsFortalezaRepository
     */
    protected $wsFortalezaRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_tj_collections_to_fortaleza';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia reporte de cobranzas para ser procesados en FORTALEZA por WS';

    protected $files;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
            CollectionRepository $collectionRepository,
            CancellationRepository $cancellationRepository,
            WsFortalezaRepository $wsFortalezaRepository
            )
    {
        parent::__construct();
        $this->collectionRepository     = $collectionRepository;
        $this->cancellationRepository   = $cancellationRepository;
        $this->wsFortalezaRepository    = $wsFortalezaRepository;
        $this->files = [];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->canceledTj();
        
        #renovacion de cobranzas
        Artisan::call('set_new_collections',['code'=>'tj']);
        
        if($this->collectionRepository->getCollectionsByNotCharged('DC')){
            $collections = $this->collectionRepository->getModel();
            
            if($collections->count() > 0){

                $info['send_collections'] = $this->wsFortalezaRepository->setFormatSecureDebitCard($collections);
                
                if($this->wsFortalezaRepository->registerSecureDebitCard($collections)){
                //if(1){
                    $response = $this->wsFortalezaRepository->getData();
                    //$response = dataExmaple('response_ws_send_collections_to_fortaleza');
                    
                    $info['response'] = [['Resultado Respuesta WS'=>json_encode((array)$response)]];
                    $title = 'Tarjeta de Debito cobros enviados '.date('Y-m-d');
                    
                    if($this->sendEmail($title, '',$this->createXls($title, $info))){
                        unlink(storage_path('exports/attachments'). '/' . str_replace(' ' , '-', strtolower($title)) . '.xlsx');
                        echo 'Proceso correcto';
                    }else
                        echo 'no se envio email de cobranas';
                }else{
                    echo 'error';
                    $response = json_encode((array)$this->wsFortalezaRepository->getErrors());
                    $this->sendEmail('Mensaje de error WS', 'No se pudo enviar cobranzas pendientes a FORTALEZA por error de respuesta WS.<br />'.$response);
                    //dd($this->wsFortalezaRepository->getErrors());
                }
            }
        }
    }

    public function sendEmail($title, $body, $file_xls=false)
    {
        $users  = ['edwinm@tubroker.com'=>'Banco Fortaleza'];
        
        $emails = [
            'ogonzalez@grupofortaleza.com.bo'   => 'Omar Gonzalez',
        ];
        
        return Mail::raw($title, function ($message) use ($file_xls, $title, $users, $emails, $body) {
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

            if($file_xls)
                $message->attach($file_xls['full']);
        });
        
    }

    public function createXls($title, $data)
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

    public function canceledTj()
    {
        if($this->collectionRepository->getCollectionsByNotCharged('DC')){
            $collections = $this->collectionRepository->getModel();
            foreach($collections as $key => $value){
                $datework = Carbon::parse($value->fee_date);
                $now = Carbon::now();
                $diffDays = $datework->diffInDays($now);
                
                if($diffDays > 90){
                    if($value->header->canceled == false){
                        $this->cancellationRepository->storeCancellationAutomatic($value->header, 1508335865, 'Anulado por mora');
                    }
                }
            }
        }

    }
}
