<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateRemodToOpTdDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_td_details', function (Blueprint $table) {
            
            $table->string('make', 100)->after('state');
            $table->string('model', 100)->after('make');
            $table->string('chassis_number', 100)->after('model');
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
