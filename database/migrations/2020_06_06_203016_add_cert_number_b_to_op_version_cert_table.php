<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertNumberBToOpVersionCertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_version_cert', function (Blueprint $table) {
            $table->string('cert_number_b', 140)->after('cert_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_version_cert', function (Blueprint $table) {
            //
        });
    }
}
