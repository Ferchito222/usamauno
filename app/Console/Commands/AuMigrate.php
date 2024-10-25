<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AuMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'au:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migracion de nuevas tasas automotores';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setYearsRates();
        if($this->years){
            $this->setYearsByCity();
            $this->setMinimumPremiums();
            dd($this->minimum);
        }
    }

    public function setMinimumPremiums()
    {
        $data = [
            ['ad_city_id'=>1, 'minimum_premium'=>400],
            ['ad_city_id'=>7, 'minimum_premium'=>400],
            ['ad_city_id'=>4, 'minimum_premium'=>400],
            ['ad_city_id'=>6, 'minimum_premium'=>300],
            ['ad_city_id'=>2, 'minimum_premium'=>300],
            ['ad_city_id'=>5, 'minimum_premium'=>300],
        ];
        
        foreach ($data as $key => $value) {
            if(!$minimum = DB::table('op_au_minimum_premiums')->where('ad_city_id', $value['ad_city_id'])->where('minimum_premium',$value['minimum_premium'])->get()){
                if(DB::table('op_au_minimum_premiums')->insert($value))
                    $minimum = DB::table('op_au_minimum_premiums')->where('ad_city_id', $value['ad_city_id'])->where('minimum_premium',$value['minimum_premium'])->get();
            }
            if($minimum){
                $this->minimum[] = $minimum[0];
            }
        }
    }

    public function setYearsByCity()
    {
        $city_rate = [
            1 => [
                ['city_id'=>1, 'rate'=>3.2],
                ['city_id'=>7, 'rate'=>3.2],
                ['city_id'=>4, 'rate'=>3.2],
                ['city_id'=>6, 'rate'=>2.2],
                ['city_id'=>2, 'rate'=>2.2],
                ['city_id'=>5, 'rate'=>2.0],
            ],
            2 => [
                ['city_id'=>1, 'rate'=>3.14],
                ['city_id'=>7, 'rate'=>3.14],
                ['city_id'=>4, 'rate'=>3.14],
                ['city_id'=>6, 'rate'=>2.16],
                ['city_id'=>2, 'rate'=>2.16],
                ['city_id'=>5, 'rate'=>1.96],
            ],
            3 => [
                ['city_id'=>1, 'rate'=>3.07],
                ['city_id'=>7, 'rate'=>3.07],
                ['city_id'=>4, 'rate'=>3.07],
                ['city_id'=>6, 'rate'=>2.11],
                ['city_id'=>2, 'rate'=>2.11],
                ['city_id'=>5, 'rate'=>1.92],
            ],
            4 => [
                ['city_id'=>1, 'rate'=>3.01],
                ['city_id'=>7, 'rate'=>3.01],
                ['city_id'=>4, 'rate'=>3.01],
                ['city_id'=>6, 'rate'=>2.07],
                ['city_id'=>2, 'rate'=>2.07],
                ['city_id'=>5, 'rate'=>1.88],
            ],
            5 => [
                ['city_id'=>1, 'rate'=>2.95],
                ['city_id'=>7, 'rate'=>2.95],
                ['city_id'=>4, 'rate'=>2.95],
                ['city_id'=>6, 'rate'=>2.03],
                ['city_id'=>2, 'rate'=>2.03],
                ['city_id'=>5, 'rate'=>1.84],
            ],            
        ];

        foreach ($this->years as $key => $value) {
            
            if($city_rate[$value->year]){
                
                foreach ($city_rate[$value->year] as $key2 => $value2) {
                    $data = [
                        'ad_rate_id'=>$value->id,
                        'ad_city_id'=>$value2['city_id'],
                        'rate'=>$value2['rate'],
                        'created_at'=>date('Y-m-d h:m:s'),
                    ];
                    if(!$rr = DB::table('op_au_rates')->where('ad_rate_id',$value->id)->where('ad_city_id',$value2['city_id'])->where('rate',$value2['rate'])->get()){
                        if(DB::table('op_au_rates')->insert($data))
                            $rr = DB::table('op_au_rates')->where('ad_rate_id',$value->id)->where('ad_city_id',$value2['city_id'])->where('rate',$value2['rate'])->get();
                    }
                    
                    if($rr)
                        $this->all_rates[] = $rr[0];
                }
            }
            
        }

    }
    
    public function setYearsRates()
    {
        DB::table('ad_rates')->where('id', 27)->update(['active'=>false]);
        foreach ([1,2,3,4,5] as $key => $value) {
            $data = [
                'year' => $value,
                'ad_retailer_product_id' => 1487341813,
                'active' => true,
            ];
            if(!$years = DB::table('ad_rates')->where('year', $value)->where('ad_retailer_product_id', 1487341813)->where('active',true)->get()){
                if(DB::table('ad_rates')->insert($data))
                    $years = DB::table('ad_rates')->where('year', $value)->where('ad_retailer_product_id', 1487341813)->where('active',true)->get();
            }

            if($years)
                $this->years[]=$years[0];
        }
    }
}
