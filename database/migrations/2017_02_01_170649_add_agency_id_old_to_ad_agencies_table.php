<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgencyIdOldToAdAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_agencies', function (Blueprint $table) {
            $table->integer('agency_id_old')->unsigned()->nullable()->after('id');

            $table->index('agency_id_old');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_agencies', function (Blueprint $table) {
            //
        });
    }
}
