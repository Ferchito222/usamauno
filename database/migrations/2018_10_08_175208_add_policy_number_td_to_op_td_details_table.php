<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPolicyNumberTdToOpTdDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_td_details', function (Blueprint $table) {
            $table->string('policy_number_td', 140)->after('op_td_header_id');
            $table->string('policy_number_mr', 140)->after('policy_number_td');
            $table->double('construction_value', 10, 2)->after('address');
            $table->double('rate_const', 10, 2)->after('construction_value');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_td_details', function (Blueprint $table) {
            //
        });
    }
}
