<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDataTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_data_table {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza informacion de tablas base de configuracion de productos';

    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->argument('type')) {
            case 'ce':
                $this->setCeData();
                break;
        }
    }  

    public function setCeData()
    {
        # Producto
        if(!$product = DB::table('ad_products')->where('code', 'ce')->where('type', 'PH')->get()){
            $data = [
                'id' => DB::table('ad_products')->max('id')+1,
                'type' => 'PH',
                'name' => 'Cesantia',
                'code' => 'ce',
                'slug' => 'cesantia',
            ];

            if(DB::table('ad_products')->insert($data))
                $product = DB::table('ad_products')->where('id', $data['id'])->get();
        }

        # Compañia
        if(!$company_product = DB::table('ad_company_products')->where('ad_product_id', $product[0]->id)->get()){
            $data = [
                'id' => DB::table('ad_company_products')->max('id')+1,
                'ad_company_id' => 1,
                'ad_product_id' => $product[0]->id,
                'active' => true,
            ];
            
            if(DB::table('ad_company_products')->insert($data))
                $company_product = DB::table('ad_company_products')->where('id', $data['id'])->get();
        }

        # Retailer
        if(!$retailer = DB::table('ad_retailer_products')->where('ad_company_product_id', $company_product[0]->id)->get()){
            $data = [
                'id' => date('U'),
                'ad_retailer_id' => 1,
                'ad_company_product_id' => $company_product[0]->id,
                'type' => 'SP',
            ];
            
            if(DB::table('ad_retailer_products')->insert($data))
                $retailer = DB::table('ad_retailer_products')->where('id', $data['id'])->get();
        }

        # Tasa
        if(!$rate = DB::table('ad_rates')->where('ad_retailer_product_id', $retailer[0]->id)->get()){
            $data = [
                'id' => DB::table('ad_rates')->max('id')+1,
                'rate_final' => 0.038,
                'ad_retailer_product_id' => $retailer[0]->id,
            ];
            
            if(DB::table('ad_rates')->insert($data))
                $rate = DB::table('ad_rates')->where('id', $data['id'])->get();
        }
        dd($rate);

    }
}
