<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Sibas\Repositories\Tj\CollectionRepository;
use Sibas\Repositories\WsFortalezaRepository;

class UpdateTjCollections extends Command
{
    /**
     * @var CollectionRepository
     */
    protected $collectionRepository;

    /**
     * @var WsFortalezaRepository
     */
    protected $wsFortalezaRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_tj_collections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra cobranzas tarjetas de denito a WS-FORTALEZA y Actualiza Cobranzas tarjetas de debito en abrenet segun respuesta WS-FORTALEZA';

    protected $files;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
            CollectionRepository $collectionRepository,
            WsFortalezaRepository $wsFortalezaRepository
            )
    {
        parent::__construct();
        $this->collectionRepository = $collectionRepository;
        $this->wsFortalezaRepository = $wsFortalezaRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dateIni = date('Y-m-d');
        $dateFin = date('Y-m-d');

        if ($this->wsFortalezaRepository->getPaymentBetweenDate($dateIni, $dateFin)){
        //if (1){
            $response = $this->wsFortalezaRepository->getData();
            //$response = dataExmaple('response_ws_update_tj_collections');
            if($response['statusCode'] == 'MSSE-0000'){
                
                foreach($response['response'] as $key => $value){
                    if ($this->collectionRepository->updateCollectionByFortaleza($value))
                        $response['response'][$key]['abrenet'] = 1;
                    else
                        $response['response'][$key]['abrenet'] = 0;
                    $response['response'][$key]['abrenet_observation'] = $this->collectionRepository->getErrors();
                }

                $info['procesados_respuesta_ws_abrenet'] = $response['response'];
                $info['response'] = [['Resultado Actualizacion de cobros en abrenet'=>json_encode((array)$response)]];
                $title = 'Actualizacion de cobros en abrenet '.date('Y-m-d');
                
                if($this->sendEmail($title, '',$this->createXls($title, $info))){
                    unlink(storage_path('exports/attachments'). '/' . str_replace(' ' , '-', strtolower($title)) . '.xlsx');
                    echo 'Proceso de actualizacion abrenet cobranzas: correcto';
                }else
                    echo 'no se envio email de cobranzas actualizadas';
            }else{
                $response = json_encode((array)$response);
                $this->sendEmail('Mensaje de error WS', 'Codigo de respuesta erronea:<br />'.$response);    
            }
            
        }else{
            echo 'error respuesta WS';
            $response = json_encode((array)$this->wsFortalezaRepository->getErrors());
            $this->sendEmail('Mensaje de error WS', 'Error de respuesta WS:<br />'.$response);
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

    public function handle_old()
    {

        $date           = date('Y-m-d',strtotime(date('Y-m-d')."- 1 days"));
        //$date           = '2022-03-22';

        $fromEmail      = env('FORTALEZA_MAIL');
        //$fromEmail      = 'cchalco@sudseguros.com';
        
        
        $this->files = [];
        if($this->readEmail($date, $fromEmail)) {
            $cont       = 0;
            $this->err  = [];
            
            if($this->files){
                
                foreach ($this->files as $key => $file) {

                    foreach ($this->readTxt($file) as $key2 => $value2) {
                        if (count($value2) == 10) {
                            if (!$this->collectionRepository->updateCollectionByFortaleza($value2)){
                                $value2['error'] = $this->collectionRepository->getErrors();
                                $this->err[] = $value2;
                            }

                        } else{
                            $value2['error'] = 'No cuenta con la informacion completa';
                            $this->err[] = $value2;
                        }
                    }   
                }
            }
            var_dump('Cobranzas erroneas');
            var_dump($this->err);
            if ($this->err)
                $this->sendEmailError($this->err);
        }
    }
    
    public function readEmail($date, $fromEmail)
    {
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        
        // email login credentials
        $this->server = env('ED_SERVER');
        $this->user   = 'edwinm@tubroker.com';
        $this->pass   = env('ED_PASS');
        
        $inbox = imap_open('{'.$this->server.'}INBOX', $this->user, $this->pass);
        #$emails = imap_search($inbox, 'FROM "jjordan@sudseguros.com" ');
        $emails = imap_search($inbox, 'FROM "'.$fromEmail.'" SINCE "'.date('j M Y', strtotime($date.'- 1 days')).'"');
        
        if($emails) {

            $count = 1;
            rsort($emails); # put the newest emails on top

            foreach ($emails as $email_number) {
                
                $header         = imap_headerinfo($inbox, $email_number);  
                
                /**/
                if($date == date('Y-m-d',strtotime($header->MailDate))){
                    $overview       = imap_fetch_overview($inbox, $email_number, 0); # get information specific to this email
                    $message        = imap_fetchbody($inbox, $email_number, 2);
                    $structure      = imap_fetchstructure($inbox, $email_number); # get mail structure
                    $attachments    = [];

                    if (isset($structure->parts) && count($structure->parts)) {

                        for ($i = 0; $i < count($structure->parts); $i++) {
                            $attachments[$i] = array(
                                'is_attachment' => false,
                                'filename' => '',
                                'name' => '',
                                'attachment' => ''
                            );

                            if ($structure->parts[$i]->ifdparameters) {

                                foreach ($structure->parts[$i]->dparameters as $object) {
                                    if (strtolower($object->attribute) == 'filename') {
                                        $attachments[$i]['is_attachment'] = true;
                                        $attachments[$i]['filename'] = $object->value;
                                    }
                                }

                            }

                            if ($structure->parts[$i]->ifparameters) {

                                foreach ($structure->parts[$i]->parameters as $object) {
                                    if (strtolower($object->attribute) == 'name') {
                                        $attachments[$i]['is_attachment'] = true;
                                        $attachments[$i]['name'] = $object->value;
                                    }
                                }

                            }

                            if ($attachments[$i]['is_attachment']) {

                                $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                                if ($structure->parts[$i]->encoding == 3) { # 3 = BASE64 encoding
                                    $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                                } elseif ($structure->parts[$i]->encoding == 4) { # 4 = QUOTED-PRINTABLE encoding
                                    $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                                }
                            }
                        }
                    }

                    #iterate through each attachment and save it
                    foreach ($attachments as $attachment) {

                        #if ($attachment['is_attachment'] == 1 && $this->filename == $attachment['name']) {
                        if ($attachment['is_attachment'] == 1 && str_contains($attachment['name'], 'reporte_cobranzas') && str_contains($attachment['name'], '.txt')) {

                            $filename = $email_number.'-'.date('Y-m-d', strtotime($header->MailDate)).'-'.$attachment['name'];

                            if (empty($filename))
                                $filename = $email_number.'-'.date('Y-m-d', strtotime($header->MailDate)).'-'.$attachment['filename'];

                            if (empty($filename))
                                $filename = time() . ".dat";

                            $folder = storage_path('exports/attachments');

                            if (!is_dir($folder)) {
                                File::makeDirectory($folder, 0777, true, true);
                            }

                            $fp = fopen($folder."/" . $filename, "w+");

                            $this->files[$email_number]['file'] = $filename; 
                            $this->files[$email_number]['path'] = $folder; 

                            fwrite($fp, $attachment['attachment']);
                            fclose($fp);
                        }
                    }
                }
            /**/    
            }
        }
        
        imap_close($inbox); # close the connection
        echo "all attachment Downloaded";
        //var_dump($this->files);
        
        if(count($this->files)>0)
            return true;
        else
            return false;
    }
    
    public function readTxt($info)
    {
        $data = [];
        if(file_exists($info['path'] . '/' . $info['file'])) {
            $content = file($info['path'] . '/' . $info['file']);
            
            if (count($content) > 1) {
                $keys = explode('|', $content[0]);
                
                foreach ($keys as $key => $value) {
                    $keys[$key] = trim($value);
                }
                
                foreach ($content as $key2 => $value2) {
                    if ($key2 > 0) {
                        foreach (explode('|', $value2) as $key3 => $value3) {
                            $data[$key2][$keys[$key3]] = trim($value3);
                        }
                    }
                }
            }
        }
        
        return $data;
    }

    public function sendEmailError($array)
    {
        $subject    = 'Fortaleza Tarjetas: cobranzas no actualizadas en abrenet';
        $title      = 'Cobranzas no actualizadas';
        $body       = view('emails.tj.not-update-collections', compact('array'));
        
        
        $users = [
            'edwinm@tubroker.com' => 'Edwin Manuel',
        ];
        $emails = [
            'edwinm@tubroker.com' => 'Edwin Manuel',
        ];
        
        Mail::raw($title, function ($message) use ($subject, $users, $emails, $body) {
            $message->from(env('MAIL_USERNAME'), 'Fortaleza');
            $message->subject($subject);
            $message->setBody($body->render(), 'text/html');

            foreach ($users as $email => $full_name) {
                $message->to($email, $full_name);
            }

            foreach ($emails as $email => $name) {
                $message->cc($email, $name);
            }
        });
    }
}
