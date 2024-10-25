<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileToOpTdHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_td_headers', function (Blueprint $table) {
            $table->text('file')->nullable()->after('ad_certificate_id');
            $table->timestamp('file_date')->nullable()->after('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_td_headers', function (Blueprint $table) {
            //
        });
    }
}
