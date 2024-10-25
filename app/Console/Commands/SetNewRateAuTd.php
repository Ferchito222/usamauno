<?php

namespace Sibas\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetNewRateAuTd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set_new_rate_au_td';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'registra nuevas tasas para TD y AU con tasa por con daños propios';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setRate(1487341813, 3.2, 1.7);
        $this->setRate(1487341814, 0, 0.22);

        $this->setCategoryBToVeicleC();
        $this->setPamarsMaxAmountAu(1487341813);
        $this->setPamarsMaxAmount(1487341814, 1300000);

        #ema
        $this->setPamarsMaxAmount(1487341815, 350000);
        #rma
        $this->setPamarsMaxAmount(1487341816, 350000);

        $this->setVehicleTypes();
        
        #au
        $this->updateFirmsGuarantees(1487341813);
        #td
        $this->updateFirmsGuarantees(1487341814);
        #ema
        $this->updateFirmsGuarantees(1487341815);
        #rma
        $this->updateFirmsGuarantees(1487341816);

        $this->setCertNUmberToGuarantees('au', 1487341813);
        $this->setCertNUmberToGuarantees('td', 1487341814);
        $this->setCertNUmberToGuarantees('ema', 1487341815);
        $this->setCertNUmberToGuarantees('rma', 1487341816);
    }

    public function setPamarsMaxAmount($rp_id, $amount)
    {
        $data = ['amount_max' => $amount];

        DB::table('ad_product_parameters')->where('ad_retailer_product_id', $rp_id)
        ->where('slug', 'GE')->update($data);
    }

    public function setPamarsMaxAmountAu($rp_id_au)
    {
        $data = [
            'amount_max_ca' => 165000,
            'amount_max_cb' => 250000,
            'year_max_ca'   => 25,
            'year_max_cb'   => 30,
        ];

        DB::table('ad_product_parameters')->where('ad_retailer_product_id', $rp_id_au)->update($data);
    }

    public function setRate($rp_id_au, $rate1, $rate_final)
    {
        if(!DB::table('ad_rates')->where('ad_retailer_product_id', $rp_id_au)->where('rate_damage_insurance', $rate1)->where('rate_final', $rate_final)->get() && 
        DB::table('ad_rates')->where('ad_retailer_product_id', $rp_id_au)->where('active', true)->update(['active' => false])){
            $data = [
                'rate_damage_insurance'     => $rate1,
                'rate_final'                => $rate_final,
                'year'                      => 2,
                'ad_retailer_product_id'    => $rp_id_au,
                'active'                    => true,
            ];
    
            DB::table('ad_rates')->insert($data);
        }
        $rates = DB::table('ad_rates')->where('ad_retailer_product_id', $rp_id_au)->where('active', true)->get();    

        return $rates;
    }

    public function setCategoryBToVeicleC()
    {
        DB::table('ad_vehicle_types')->where('ad_retailer_product_category_id', 3)->update(['ad_retailer_product_category_id'=>2]);
    }

    public function setVehicleTypes()
    {
        $news = [
            ['name'=>'VOLQUETA','code'=>'VL'],
        ];
        foreach ($news as $key => $value) {
            
            if(!DB::table('ad_vehicle_types')->where('vehicle',$value['name'])->get()){
                $data = [
                    'vehicle'=>$value['name'],
                    'equivalence'=>$value['code'],
                    'ad_retailer_product_category_id'=>2,
                    'active'=>true,
                ];
                DB::table('ad_vehicle_types')->insert($data);
            }
        }
        $vehicles = [
            'VAGONETA',
            'AUTOMOVIL',
            'MINIBUS',
            'CAMIONETA',
            'JEEP',
            'FURGONETA',
            'CAMION',
            'BUS',
            'TRACTO CAMION',
            'CHASIS COMBINADO',
            'VOLQUETA',
            'CAMION FRIGORIFICO',
            'CAMION FURGON',
            'FURGON',
            'MICRO BUS',
            'MINI FURGON',
            'OMNIBUS',
            'OTROS',
        ];

        DB::table('ad_vehicle_types')->update(['active'=>false]);

        foreach ($vehicles as $key => $value) {
            if(DB::table('ad_vehicle_types')->where('vehicle', $value)->where('active', false)->get())
                DB::table('ad_vehicle_types')->where('vehicle', $value)->update(['active'=>true]);
        }
    }

    public function updateFirmsGuarantees($rp_id)
    {
        $firms = [
            4 => [
                'city_id' => 4,
                'firma_a' => 'CB_A.jpg',
                'pie_a' => 'Glenda Jimena Saavedra Muñoa<br />Jefe Comercial de Finanzas',
                'firma_b' => 'CB_B.jpg',
                'pie_b' => 'Maria Rene Martinez Brañez<br />Jefe de Negocios',
            ],
            1 => [
                'city_id' => 1,
                'firma_a' => 'LP_A.jpg',
                'pie_a' => 'José Eguez Rodriguez<br />Asistente de emisión',
                'firma_b' => 'LP_B.jpg',
                'pie_b' => 'Luis F. Trigo Cuellar<br />Gerente Región Occidente',
            ],
            2 => [
                'city_id' => 2,
                'firma_a' => 'OR_A.jpg',
                'pie_a' => 'Tatiana Santos Gutierrez<br />Ejecutiva de Cuentas Corporativas',
                'firma_b' => 'OR_B.jpg',
                'pie_b' => 'Luis F. Trigo Cuellar<br />Gerente Región Occidente',
            ],
            7 => [
                'city_id' => 7,
                'firma_a' => 'SC_A.jpg',
                'pie_a' => 'Rosario Gutierrez Pérez<br />Asistente de emisión',
                'firma_b' => 'SC_B.jpg',
                'pie_b' => 'Eidy Yoany Salvatierra Vargas<br />Gerente Comercial',
            ],
            5 => [
                'city_id' => 5,
                'firma_a' => 'SU_A.jpg',
                'pie_a' => 'Paola Mercado Encinas<br />Jefe Comercial',
                'firma_b' => 'SU_B.jpg',
                'pie_b' => 'Boris Reinaldo Valda Gutierrez<br />Gerente Regional',
            ],
            6 => [
                'city_id' => 6,
                'firma_a' => 'TJ_A.jpg',
                'pie_a' => 'Alvaro Martinez Aparicio<br />Encargado de Emisión y Archivo',
                'firma_b' => 'TJ_B.jpg',
                'pie_b' => 'Claudia Suica<br />Jefe de Negocios',
            ],
        ];

        $ad_certificate = DB::table('ad_certificates')->where('ad_retailer_product_id', $rp_id)->where('active',true)->first();
        
        if($version_cert = DB::table('op_version_cert')->where('ad_retailer_product_id', $rp_id)->where('ad_certificate_id',$ad_certificate->id)->get()){
            foreach ($version_cert as $key => $value) {
                $city_id = ($value->ad_city_id==10)?1:$value->ad_city_id;
                $data = [
                    'firma_a'=>$firms[$city_id]['firma_a'],
                    'pie_a'=>$firms[$city_id]['pie_a'],
                    'firma_b'=>$firms[$city_id]['firma_b'],
                    'pie_b'=>$firms[$city_id]['pie_b'],
                ];
                
                DB::table('op_version_cert')->where('id', $value->id)->update($data);
            }
        }
        
    }

    public function setCertNUmberToGuarantees($code, $rp_id)
    {
        $arr = [
            'au' => [
                [
                    'city_id' => 4,
                    'city' => 'CB',
                    'cert_number' => 'AUC-CB0501-3',
                ],
                [
                    'city_id' => 1,
                    'city' => 'LP',
                    'cert_number' => 'AUC-LP0201-2',
                ],
                [
                    'city_id' => 10,
                    'city' => 'LP',
                    'cert_number' => 'AUC-LP0201-2',
                ],
                [
                    'city_id' => 2,
                    'city' => 'OR',
                    'cert_number' => 'AUC-OR0401-6',
                ],
                [
                    'city_id' => 7,
                    'city' => 'SC',
                    'cert_number' => 'AUC-SC0101-1',
                ],
                [
                    'city_id' => 5,
                    'city' => 'CH',
                    'cert_number' => 'AUC-SU0601-4',
                ],
                [
                    'city_id' => 6,
                    'city' => 'TJ',
                    'cert_number' => 'AUC-TJ0301-5',
                ],
            ],
            'ema' => [
                [
                    'city_id' => 4,
                    'city' => 'CB',
                    'cert_number' => 'EMAC-CB0501-3',
                ],
                [
                    'city_id' => 1,
                    'city' => 'LP',
                    'cert_number' => 'EMAC-LP0201-2',
                ],
                [
                    'city_id' => 10,
                    'city' => 'LP',
                    'cert_number' => 'EMAC-LP0201-2',
                ],
                [
                    'city_id' => 2,
                    'city' => 'OR',
                    'cert_number' => 'RMQC-OR0401-6',
                ],
                [
                    'city_id' => 7,
                    'city' => 'SC',
                    'cert_number' => 'RMQC-SC0101-1',
                ],
                [
                    'city_id' => 5,
                    'city' => 'CH',
                    'cert_number' => 'RMQC-SU0601-4',
                ],
                [
                    'city_id' => 6,
                    'city' => 'TJ',
                    'cert_number' => 'RMQC-TJ0301-5',
                ],
            ],
            'rma' => [
                [
                    'city_id' => 4,
                    'city' => 'CB',
                    'cert_number' => 'RMQC-CB0501-3',
                ],
                [
                    'city_id' => 1,
                    'city' => 'LP',
                    'cert_number' => 'RMQC-LP0201-2',
                ],
                [
                    'city_id' => 10,
                    'city' => 'LP',
                    'cert_number' => 'RMQC-LP0201-2',
                ],
                [
                    'city_id' => 2,
                    'city' => 'OR',
                    'cert_number' => 'RMQC-OR0401-6',
                ],
                [
                    'city_id' => 7,
                    'city' => 'SC',
                    'cert_number' => 'RMQC-SC0101-1',
                ],
                [
                    'city_id' => 5,
                    'city' => 'CH',
                    'cert_number' => 'RMQC-SU0601-4',
                ],
                [
                    'city_id' => 6,
                    'city' => 'TJ',
                    'cert_number' => 'RMQC-TJ0301-5',
                ],
            ],
            'td' => [
                [
                    'city_id' => 4,
                    'city' => 'CB',
                    'cert_number' => 'TOA-CB0501-8862',
                ],
                [
                    'city_id' => 1,
                    'city' => 'LP',
                    'cert_number' => 'TOA-LP0201-8860',
                ],
                [
                    'city_id' => 10,
                    'city' => 'LP',
                    'cert_number' => 'TOA-LP0201-8860',
                ],
                [
                    'city_id' => 2,
                    'city' => 'OR',
                    'cert_number' => 'TOA-OR0401-8864',
                ],
                [
                    'city_id' => 7,
                    'city' => 'SC',
                    'cert_number' => 'TOA-SC0101-8863',
                ],
                [
                    'city_id' => 5,
                    'city' => 'CH',
                    'cert_number' => 'TOA-SU0601-8865',
                ],
                [
                    'city_id' => 6,
                    'city' => 'TJ',
                    'cert_number' => 'TOA-TJ0301-8861',
                ],
            ],
        ];
        
        foreach ($arr[$code] as $key => $value) {
            DB::table('op_version_cert')
            ->where('active', true)
            ->where('ad_retailer_product_id', $rp_id)
            ->where('ad_city_id',$value['city_id'])->update(['cert_number'=>$value['cert_number']]);
        }

    }
}
