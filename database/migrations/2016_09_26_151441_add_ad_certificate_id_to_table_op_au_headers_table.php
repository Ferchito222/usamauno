<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdCertificateIdToTableOpAuHeadersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_au_headers', function (Blueprint $table) {
            $table->integer('ad_certificate_id')->unsigned()->nullable()->after('read');

            $table->foreign('ad_certificate_id')->references('id')->on('ad_certificates');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_au_headers', function (Blueprint $table) {
            //
        });
    }
}
