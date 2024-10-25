<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\WsFortalezaRepository;

class UpdateWsClients extends Command
{
    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var WsFortalezaRepository
     */
    protected $wsFortalezaRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_ws_clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza dni de cliente en base a respuesta WS-fortaleza';

    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
            ClientRepository $clientRepository,
            WsFortalezaRepository $wsFortalezaRepository
            )
    {
        parent::__construct();
        $this->clientRepository = $clientRepository;
        $this->wsFortalezaRepository     = $wsFortalezaRepository;
        
        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        foreach($this->getDniErr() as $key => $val){
            
            if($this->clientRepository->getClientById($val)){
                $clients = [$this->clientRepository->getModel()];
                
                $err = 0;
                $ok = 0;
                foreach($clients as $key2 => $value2) {
                    
                    if($this->wsFortalezaRepository->getWsRest('id_cliente',$value2->code)){
                        $data   = $this->wsFortalezaRepository->getData();
                        if(isset($data['response']['documentId'])){
                            $dniws = $value2->dni.'|'.$value2->extension;
                            echo '----';
                            print_r($dniws);
                            echo '----';
                            if($value2->update(['dni_history' => $dniws, 'dni'=>getDni($data['response']['documentId']), 'extension'=>getExtension($data['response']['documentId'])]))
                                $ok++;
                            else
                                $err++;
                        }else
                            $err++;
                        
                    }else
                        $err++;
                }
                echo 'registros correctos: '.$ok.'----';
                echo 'registros erroneos: '.$err;
            }
        }
        exit;
        /**
        if($this->clientRepository->getClientByCode()){
            $clients = $this->clientRepository->getModel();
            $err = 0;
            $ok = 0;
            foreach($clients as $key => $value) {
                
                if($this->wsFortalezaRepository->getWsRest('id_cliente',$value->code, 'estatico')){
                    $data   = $this->wsFortalezaRepository->getData();
                    if(isset($data['response']['documentId'])){
                        $dniws = getDni($data['response']['documentId']).'|'.getExtension($data['response']['documentId']);
                        echo '<pre>';
                        print_r($dniws);
                        echo '</pre>';
                        if($value->update(['dni_history' => $dniws]))
                            $ok++;
                        else
                            $err++;
                    }else
                        $err++;
                    
                }else
                    $err++;
            }
            echo 'registros correctos: '.$ok.'----';
            echo 'registros erroneos: '.$err;
        }
        /**/
    }  

    public function getDniErr()
    {
        $arr = '[1540422777,1540423307,1540427455,1540428375,1540429599,1540430134,1540430316,1540430454,1540430998,1540431014,1540431050,1540431074,1540431395,1540431420,1540431439,
        1540431459,1540431462,1540431669,1540431694,1540431724,1540431767,1540431772,1540431809,1540431824,1540431924,1540432108,1540432215,1540432230,1540432235,1540432452,1540432467,1540432473,
        1540432542,1540432552,1540432578,1540432625,1540432736,1540432744,1540432810,1540432820,1540433011,1540433052,1540433409,1540433458,1540433566,1540433691,1540433757,1540433761,
        1540433796,1540433990,1540434149]';
        
        return json_decode($arr,true);
    }
}
