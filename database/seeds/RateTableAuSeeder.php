<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateTableAuSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        #ejecuta tabla insert   actualizacion de tasas
        $this->execute('auto', 1487341813 ,22, 14, 2.90, 12);
        $this->execute('td', 1487341814 ,23, 19, 0.180, 15);
        $this->execute('ema', 1487341815 ,24, 20, 0.40, 13);
        $this->execute('rma', 1487341816 ,25, 21, 0.40, 14);
    }
    
    /**
     * 
     * @param type $code: codigo de producto
     * @param type $retailer_product_id: id retailer_product
     * @param type $new_rate_id: nuevo ID de tabla ad_rates
     * @param type $prev_rate_id: ID que se debe desactivar
     * @param type $new_rate: nueva tasa ejemplo 2.90
     * @param type $prev_ad_certificate_id: ID de ad_certificates que se debe desactivar
     */ 
    public function execute($code, $retailer_product_id, $new_rate_id, $prev_rate_id, $new_rate, $prev_ad_certificate_id) 
    {
        DB::table('ad_rates')->where('id', $prev_rate_id)->update(['active' => 0]);

        #insert new rate
        $insertRate = $this->insertRate($new_rate_id, $retailer_product_id, $new_rate);
        
        if ($code == 'auto' && $insertRate) {
            DB::table('ad_au_increments')->insert([
                'ad_rate_id' => $new_rate_id,
                'ad_retailer_product_category_id' => 1,
                'increment' => 0,
            ]);

            DB::table('ad_au_increments')->insert([
                'ad_rate_id' => $new_rate_id,
                'ad_retailer_product_category_id' => 2,
                'increment' => 0.3,
            ]);

            DB::table('ad_au_increments')->insert([
                'ad_rate_id' => $new_rate_id,
                'ad_retailer_product_category_id' => 3,
                'increment' => 0.3,
            ]);
        }
        
        DB::table('ad_certificates')->where('id', $prev_ad_certificate_id)->update(['active' => 0]);
        
        #insert new cert by retailer AU
        $this->insertNewCet($retailer_product_id, $code);
        
        #update and insert version cert
        $this->updateVersionCert($retailer_product_id, $prev_ad_certificate_id);
    }
    
    public function insertNewCet($retailer_product_id, $code) 
    {
        $adCertificate = DB::table('ad_certificates')
                ->where('ad_retailer_product_id', $retailer_product_id)
                ->where('active', true)->get();
        if(!$adCertificate){
            DB::table('ad_certificates')->insert([
                'ad_retailer_product_id' => $retailer_product_id,
                'version' => 1,
                'template' => 'certificate_'.$code.'-v3',
                'css' => 'estilo',
                'active' => true,
            ]);
        }
    }
    
    public function insertRate($rate_id, $retailer_product_id, $newRate) 
    {
        if(!DB::table('ad_rates')->where('id', $rate_id)->get()) {
            return DB::table('ad_rates')->insert([
                'id' => $rate_id,
                'rate_company' => 0,
                'rate_bank' => 0,
                'rate_final' => $newRate,
                'year' => 2,
                'ad_retailer_product_id' => $retailer_product_id,
                'ad_credit_product_id' => null,
                'ad_coverage_id' => null,
                'active' => true
            ]);
        }
        return false;
    }

    public function updateVersionCert($retailer_product_id, $ad_certificates_id) 
    {
        $certificate = DB::table('ad_certificates')
                        ->where('active', true)
                        ->where('ad_retailer_product_id', $retailer_product_id)->get();
        
        if(!DB::table('op_version_cert')->where('ad_certificate_id', $certificate[0]->id)->get()) {
            
            $versionCert = DB::table('op_version_cert')
                        ->where('ad_certificate_id', $ad_certificates_id)->get();
            
            $firma = $this->getPie();
            
            foreach ($versionCert as $key => $value) {
                DB::table('op_version_cert')
                        ->where('id', $value->id)
                        ->update(['active' => 0]);

                DB::table('op_version_cert')->insert([
                    'ad_certificate_id' => $certificate[0]->id,
                    'ad_retailer_product_id' => $retailer_product_id,
                    'ad_city_id' => $value->ad_city_id,
                    'cert_number' => $value->cert_number,
                    'firma_a' => $value->firma_a,
                    'pie_a' => $firma[$value->ad_city_id]['pie_a'],
                    'firma_b' => $value->firma_b,
                    'pie_b' => $firma[$value->ad_city_id]['pie_b'],
                    'active' => true
                ]);
            }
        }
    }
    
    public function getPie()
    {
        return [
            7=>[
                'pie_a'=>'Encargado de Emisión y Archivo',
                'pie_b'=>'Gerente Comercial',
            ],
            4=>[
                'pie_a'=>'Encargado de Emisión y Archivo',
                'pie_b'=>'Jefe de Negocios',
            ],
            1=>[
                'pie_a'=>'Asistente de Emisión',
                'pie_b'=>'Gerente Región Occidente',
            ],
            5=>[
                'pie_a'=>'Gerente Regional',
                'pie_b'=>'Gerente Región Occidente',
            ],
            10=>[
                'pie_a'=>'Asistente de Emisión',
                'pie_b'=>'Gerente Región Occidente',
            ],
            6=>[
                'pie_a'=>'Encargado de Emisión y Archivo',
                'pie_b'=>'Gerente Regional',
            ],
            2=>[
                'pie_a'=>'Jefe de Negocios',
                'pie_b'=>'Gerente Región Occidente',
            ],
        ];
    }

}
