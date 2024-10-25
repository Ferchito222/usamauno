<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyUseToOpEmaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_ema_details', function (Blueprint $table) {
            $table->string('property_use', 100)->after('insured_value');
            $table->text('description')->after('property_use');
            $table->string('serial_number', 100)->after('description');
            $table->string('city', 100)->after('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_ema_details', function (Blueprint $table) {
            //
        });
    }
}
