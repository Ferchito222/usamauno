<?php

namespace Sibas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAgencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_agencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza agencias donde fueron emitidos los registros';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->updateRetailerAgencies('de');
        $this->updateRetailerAgencies('au');
        $this->updateRetailerAgencies('td');
        $this->updateRetailerAgencies('ema');
        $this->updateRetailerAgencies('rma');
        $this->updateRetailerAgencies('tj');
        
    }

    public function updateRetailerAgencies($code)
    {
        $this->header = [];
        if($this->getHeaderNotAgencies($code)){
            $ok=0;
            $err=0;
            foreach($this->header as $key => $value){
                
                if(DB::table('op_'.$code.'_headers')->where('id', $value->id)->update(['ad_retailer_city_agency_id' => $this->getRetailerCityAgency($value->ad_user_id)]))
                    $ok++;
                else
                    $err++;
            }
            var_dump($code, $ok, $err);
        }
    }

    public function getHeaderNotAgencies($code)
    {
        $this->header = DB::table('op_'.$code.'_headers')->select('id', 'ad_retailer_city_agency_id', 'ad_user_id')->where('ad_retailer_city_agency_id', false)->get();
        if(count($this->header)>0)
            return true;
        else
            return false;
    }

    public function getRetailerCityAgency($user_id)
    {
        $user = DB::table('ad_users')->where('id', $user_id)->first();
        $retailer_city = DB::table('ad_retailer_cities')->where('ad_city_id', $user->ad_city_id)->first();
        $retailer_city_agencies = DB::table('ad_retailer_city_agencies')
                                ->where('ad_retailer_city_id', $retailer_city->id)
                                ->where('ad_agency_id', $user->ad_agency_id)
                                ->first();
        return $retailer_city_agencies->id;
    }
    
}
