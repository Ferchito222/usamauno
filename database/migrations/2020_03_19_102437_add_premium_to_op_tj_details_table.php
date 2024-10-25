<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPremiumToOpTjDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_tj_details', function (Blueprint $table) {
            $table->double('premium', 20, 2)->after('card');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_tj_details', function (Blueprint $table) {
            //
        });
    }
}
