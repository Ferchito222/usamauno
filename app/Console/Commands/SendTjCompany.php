<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Sibas\Repositories\Tj\HeaderRepository;
use Sibas\Repositories\WsRepository;

class SendTjCompany extends Command
{
    /**
     * @var HeaderRepository
     */
    protected $headerRepository;
    
    /**
     * @var WsRepository
     */
    protected $wsRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_tj_company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia emisiones Tarjeta de debito a la compañia';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
            HeaderRepository $headerRepository,
            WsRepository $wsRepository
            )
    {
        parent::__construct();
        $this->headerRepository             = $headerRepository;
        $this->wsRepository                 = $wsRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->headerRepository->getHeaderNotSendCompany()){
            $headers = $this->headerRepository->getModel();
            $datas = [];
            foreach ($headers as $key => $header) {
                if ($header->details->first()->card === 'DC' && $header->cpn_send_company === 0) {
                    $datas[] = $header;
                }
            }
            
            if($datas){
                
                $lot_number = DB::table('op_tj_headers')->max('cpn_lot_number') + 1;

                if ($this->wsRepository->sendDebitCardToCompany($datas, $lot_number)) {
                    $result = $this->wsRepository->getData();
                    echo 'Result OK<pre>';
                    print_r($result);
                    echo '</pre>';
                    $this->updateResultWs($datas,$lot_number, json_encode($result));
                } else
                    echo '<br />--ingresando-->Error al enviar emision a la compañia.';
            }
        }
    }
    
    public function updateResultWs($datas,$lot_number, $result)
    {
        foreach ($datas as $key => $value) {
            $arr = [
                'cpn_send_company' => true,
                'cpn_lot_number' => $lot_number,
                'cpn_result_ws' => $result,
            ];
            echo 'Update DB: <pre>';
            print_r($arr);
            echo '</pre>';
            if ($value->update($arr))
                echo '<br />--ingresando-->Actualizado Correctamente';
            else
                echo '<br />--ingresando-->Error al actualizar Header Tj';
        }
    }

}
