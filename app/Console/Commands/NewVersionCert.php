<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NewVersionCert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new_version_cert {rp_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra nueva version de certificado por producto';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->argument('rp_id')){
            $this->rp_id     = $this->argument('rp_id');
            
            if($this->retailerProduct())
                if($this->certificates()){
                //if(true){
                    
                    #solo para registrar nuevo permiso
                    //if($this->permissions('Descargar Certificado Publico', 'RPRIN'))
                      //  echo json_encode($this->permission);
                    if($this->versionCert()){
                        echo json_encode($this->version_cert);
                    }
                    
                }
        }
    }

    public function retailerProduct()
    {
        if($this->retailerProduct = DB::table('ad_retailer_products')->where('id', $this->rp_id)->get()){
            $this->retailerProduct = $this->retailerProduct[0];
            return true;
        }

        return false;
    }

    public function certificates()
    {
        if(!$this->certificates = DB::table('ad_certificates')->where('ad_retailer_product_id', $this->rp_id)->where('active', true)->whereDate('created_at', '=', date('Y-m-d'))->get()){
            $certificates = DB::table('ad_certificates')->where('ad_retailer_product_id', $this->rp_id)->where('active', true)->get();
            
            foreach ($certificates as $key => $value) {
                preg_match_all('!\d+!', $value->template, $matches);
                $nro = $matches[0][count($matches[0])-1];
                $newTemp = str_replace($nro, $nro+1, $value->template);
                $data = (array)$value;
                unset($data['id']);
                $data['template'] = $newTemp; 
                $data['created_at'] = date('Y-m-d'); 
                $data['updated_at'] = date('Y-m-d'); 

                if(DB::table('ad_certificates')->insert($data))
                    DB::table('ad_certificates')->where('id',$value->id)->update(['active'=>false]);
            }
            $this->certificates = DB::table('ad_certificates')->where('ad_retailer_product_id', $this->rp_id)->where('active', true)->get();
        }

        if($this->certificates)
            return true;
        else
            return false;
        
    }

    public function permissions($name, $slug)
    {
        if(!$this->permission = DB::table('ad_permissions')->where('slug', $slug)->get()){
            $data = [
                'name' => $name,
                'slug' => $slug,
                'model' => 'user',
                'active' => true,
            ];
            if(DB::table('ad_permissions')->insert($data))
                $this->permission = DB::table('ad_permissions')->where('slug', $data['slug'])->get();
        }
        if($this->permission){
            $this->permission = $this->permission[0];
            return true;
        }
        return false;
    }

    public function versionCert()
    {
        foreach ($this->certificates as $key => $value) {
            
            if($version_cert1 = DB::table('op_version_cert')->where('ad_retailer_product_id', $this->rp_id)->where('active', true)->get()){
                if(!$version_cert2 = DB::table('op_version_cert')->where('ad_retailer_product_id', $this->rp_id)->where('ad_certificate_id', $value->id)->get()){
                    foreach ($version_cert1 as $key2 => $value2) {
                        
                        $data = (array)$value2;
                        unset($data['id']);
                        $data['ad_certificate_id'] = $value->id;
                        $data['created_at'] = date('Y-m-d');
                        $data['updated_at'] = date('Y-m-d');
                        if(DB::table('op_version_cert')->insert($data))
                            DB::table('op_version_cert')->where('id',$value2->id)->update(['active'=>false]);
                            
                    }
                }
            }

            $this->version_cert = DB::table('op_version_cert')->where('ad_retailer_product_id', $this->rp_id)->where('active', true)->get();
            if($this->version_cert)
                return true;
            else
                return false;
        }
    }

}
