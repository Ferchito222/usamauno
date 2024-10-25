<?php

namespace Sibas\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Repositories\De\HeaderRepository;

class AppMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'app migrate';
    
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
    public function __construct(HeaderRepository $headerRepository)
    {
        parent::__construct();
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
        $this->migrate_certificate();
    }

    public function migrate_certificate()
    {   ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $this->createCert();
        $certificate = DB::table('ad_certificates')->where('template', 'comercial-old-v10')->first();
        
        if(file_exists(storage_path('exports/policies.xls'))){
            Excel::load(storage_path('exports/policies.xls'), function($reader) {
                $results = $reader->get();
                $this->array = $results->toArray(); 
            });
    
            $data = $this->array[0];
            $policies = [];
            foreach ($data as $key => $value) {
                $data[$key]['cert_id'] = '';
                echo '------->'.$key;
                if($this->headerRepository->getHeader($value)){
                    $header = $this->headerRepository->getModel();

                    $message = '';
                    if($header->ad_certificate_id==48){
                        if($this->headerRepository->updateCert($header->id, $certificate->id))
                            $message = 'actualizado correctamente';
                    }elseif($header->ad_certificate_id == $certificate->id)
                        $message = 'actualizado correctamente';

                    $data[$key]['cert_id'] = ($header->ad_certificate_id == $certificate->id)?48:$header->ad_certificate_id; 
                    $data[$key]['observacion'] = $message; 
                }
            }

            
            $title          = 'Policy'.date("Y-m-d");
            $fileName       = 'migracion';
            $emailsTo       = ['EdwinM@tubroker.com'=>'Edwin Manuel'];
            $emailsCc       = [];

            $file1 = $this->headerRepository->createXlsByArray($fileName, [$fileName => $data]);

            if($file1){
                if($this->headerRepository->sendEmail($title, false, [$file1], false, $emailsTo, $emailsCc)){
                    unlink($file1['full']);
                    echo 'Correo enviado';
                    return true;
                }else
                    echo 'Correo no enviado';
            }
            
        }else{
            echo 'no existe archivo';
        }
        
    }

    public function createCert()
    {
        if(!DB::table('ad_certificates')->where('template', 'comercial-old-v10')->count()){
            $data = [
                'ad_retailer_product_id' => 1474321374,
                'version' => 1,
                'template' => 'comercial-old-v10',
                'active' => false,
                'created_at' => date('Y-m-d h:m:s'),
            ];
            DB::table('ad_certificates')->insert($data);
        }
    }
    
}
