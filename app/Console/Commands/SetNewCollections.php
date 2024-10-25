<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SetNewCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set_new_collections {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra nuevos cobros para emisiones sin cobranzas pendientes.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->argument('code')){
            $code       = $this->argument('code');
            $headers    = DB::table('op_'.$code.'_headers')->where('issued', true)->where('canceled', false)->get();
            $ok = 0;
            $error = 0;
            foreach ($headers as $key => $value) {
                $collections = DB::table('op_'.$code.'_collections')->where('op_'.$code.'_header_id', $value->id)->where('charged', false)->get();
                if(count($collections) == 0){
                    $max = DB::table('op_'.$code.'_collections')->where('op_'.$code.'_header_id', $value->id)->max('fee_number');
                    if($max){
                        $arr = DB::table('op_'.$code.'_collections')->where('op_'.$code.'_header_id', $value->id)->where('fee_number', $max)->first();
                    
                        $data = [
                            'op_'.$code.'_header_id'    => $value->id,
                            'fee_number'                => $max+1,
                            'fee_date'                  => date("Y-m-d",strtotime($arr->fee_date."+ 1 month")),
                            'fee_amount'                => $arr->fee_amount,
                            'created_at'                => date('Y-m-d'),
                        ];
                        if(DB::table('op_'.$code.'_collections')->insert($data))
                            $ok++;
                        else
                            $error++;
                    }
                }
            }
        }
        echo 'nuevos cobros: '.$ok;
        echo ' ---- erroneos: '.$error;
    }

}
