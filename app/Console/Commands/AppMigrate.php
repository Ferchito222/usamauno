<?php

namespace Sibas\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'app migrate';
    
    public $array;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $this->updateExchange(6.96);
        $this->clearData('au');
        $this->clearData('de');
        $this->clearData('ema');
        $this->clearData('rma');
        $this->clearData('td');
        $this->clearData('tj');
        $this->clearData('vi');
        $this->deleteOters();
        $this->userDelete();
        
        echo 'fin-consultas';
    }

    public function userDelete()
    {   
        $restrict = [
            'admin',
            'edwinadm',
            'compania',
            'vendedor',
            'usama',
            'xmendoza',
            'cvelasco',
            'rcallizaya',
            'jvargas',
            'Jzabala',
            'wbaina',
            'wgamboa',
            'amiranda',
            'cmercado',
        ];

        $users = DB::table('ad_users')->select('id')->whereIn('username', $restrict)->get();
        $ids = [];
        foreach ($users as $key => $value) {
            $ids[] = $value->id;
        }

        $retailer_user = DB::table('ad_retailer_users')->select('id')->whereNotIn('ad_user_id', $ids)->get();
        $r_ids = [];
        foreach ($retailer_user as $key => $value) {
            $r_ids[] = $value->id;
        }
        DB::table('ad_retailer_user_products')->whereIn('ad_retailer_user_id', $r_ids)->delete();

        DB::table('ad_retailer_users')->whereNotIn('ad_user_id', $ids)->delete();
        DB::table('ad_user_permissions')->whereNotIn('ad_user_id', $ids)->delete();
        DB::table('ad_user_profiles')->whereNotIn('ad_user_id', $ids)->delete();

        DB::table('ad_users')->whereNotIn('id', $ids)->delete();
    }

    public function updateExchange($amount)
    {   
        DB::table('ad_exchange_rates')->where('id',1)->update(['bs_value'=>$amount]);
    }

    public function deleteOters()
    {   
        DB::table('ad_emails')->delete();
        DB::table('op_mc_answers')->delete();
        DB::table('op_clients')->delete();
    }

    public function clearData($code)
    {   
        if($code == 'au'){
            DB::table('op_au_rates')->delete();
            DB::table('op_au_minimum_premiums')->delete();
            DB::table('ad_au_increments')->delete();
        }

        DB::table('op_'.$code.'_cancellations')->delete();

        if (Schema::hasTable('op_'.$code.'_facultatives'))
            DB::table('op_'.$code.'_facultatives')->delete();

        if (Schema::hasTable('op_'.$code.'_collections'))
            DB::table('op_'.$code.'_collections')->delete();

        if (Schema::hasTable('op_'.$code.'_beneficiaries'))
            DB::table('op_'.$code.'_beneficiaries')->delete();

        if (Schema::hasTable('op_'.$code.'_detail_coverages'))
            DB::table('op_'.$code.'_detail_coverages')->delete();

        if (Schema::hasTable('op_'.$code.'_responses'))
            DB::table('op_'.$code.'_responses')->delete();

        if (Schema::hasTable('op_'.$code.'_rejected_customers'))
            DB::table('op_'.$code.'_rejected_customers')->delete();

        DB::table('op_'.$code.'_details')->delete();
        DB::table('op_'.$code.'_headers')->delete();
    }
    
}
