<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdPolicyIdToOpDeHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_headers', function (Blueprint $table) {
            $table->string('version_number', 100)->after('policy_number');
            $table->integer('ad_policy_id')->unsigned()->nullable()->after('version_number');
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
