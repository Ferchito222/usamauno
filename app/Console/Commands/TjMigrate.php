<?php

namespace Sibas\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Repositories\Tj\CollectionRepository;
use Sibas\Repositories\Tj\HeaderRepository;
use Sibas\Repositories\WsRepository;
use Sibas\Repositories\Client\ClientRepository;

class TjMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tj:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migracion de seguros Tarjetas de credito desde un xls';
    
    /**
     *@var CollectionRepository
     */
    protected $collectionRepository;
    
    /**
     *@var HeaderRepository
     */
    protected $headerRepository;

    /**
     *@var WsRepository
     */
    protected $ws;

    /**
     *@var ClientRepository
     */
    protected $clientRepository;
    
    public $array;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
            CollectionRepository $collectionRepository,
            HeaderRepository $headerRepository,
            WsRepository $ws,
            ClientRepository $clientRepository
            )
    {
        parent::__construct();
        $this->collectionRepository = $collectionRepository;
        $this->headerRepository = $headerRepository;
        $this->ws = $ws;
        $this->clientRepository = $clientRepository;
        $this->array = [];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   $file = 'tarjetas_de_credito_20240223.xlsx';
        if($data = $this->headerRepository->loadFile($file)){
            $this->completeInformation($data[0]);
        }
    }

    public function completeInformation($data)
    {
        foreach ($data as $key => $value) {
            echo '--'.$key.'-->'.$value['cage'];
            if($this->ws->debitCard((int)$value['cage'])){
                dd($value, $this->ws->getData());
                $ress = $this->ws->getData();
                var_dump($ress);
                $data[$key]+=$this->clientRepository->setFormData($ress);
            }else{
                dd('value', $value);
                $data[$key]+=$this->clientRepository->setFormData(false);
            }
        }
        
        $this->clientRepository->sendExcelAttached('completeInfo', $data, setting('emailTo'));
    }
}
