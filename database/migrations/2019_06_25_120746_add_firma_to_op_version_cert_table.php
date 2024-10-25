<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirmaToOpVersionCertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_version_cert', function (Blueprint $table) {
            $table->string('firma_a', 100)->after('cert_number');
            $table->string('pie_a', 100)->after('firma_a');
            $table->string('firma_b', 100)->after('pie_a');
            $table->string('pie_b', 100)->after('firma_b');
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
