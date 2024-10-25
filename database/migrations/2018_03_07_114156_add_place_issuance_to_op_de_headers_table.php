<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlaceIssuanceToOpDeHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_headers', function (Blueprint $table) {
            $table->unsignedInteger('place_issuance')->nullable()->after('date_issue');

            $table->foreign('place_issuance')->references('id')->on('ad_cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_headers', function (Blueprint $table) {
            //
        });
    }
}
