<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorOpTdDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_td_details', function (Blueprint $table) {
            $table->enum('matter_insured', array_keys(config('base.property_types')))->after('op_td_header_id');
            $table->longText('matter_description')->after('matter_insured');
            $table->integer('number')->after('matter_description');
            $table->enum('use', array_keys(config('base.property_uses')))->after('number');
            $table->double('construction_value', 20, 2)->after('insured_value');
            $table->double('land_value', 20, 2)->after('construction_value');
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
