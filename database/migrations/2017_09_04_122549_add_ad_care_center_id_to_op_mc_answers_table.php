<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdCareCenterIdToOpMcAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_mc_answers', function (Blueprint $table) {
            $table->integer('ad_care_center_id')->unsigned()->nullable()->after('mc_certificate_id');

            $table->foreign('ad_care_center_id')->references('id')->on('ad_care_centers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_mc_answers', function (Blueprint $table) {
            //
        });
    }
}
