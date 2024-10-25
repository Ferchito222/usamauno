<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateMonthToOpTdDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_td_details', function (Blueprint $table) {
            $table->double('rate_month', 10, 4)->after('rate');
            $table->double('premium_month', 20, 2)->after('premium');
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
