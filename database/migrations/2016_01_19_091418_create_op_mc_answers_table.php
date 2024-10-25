<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpMcAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_mc_answers', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('medical_certificate_number')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->integer('mc_certificate_id')->unsigned();
            $table->string('center_attention', 140);
            $table->time('time_attention');
            $table->string('contact_person', 140);
            $table->string('contact_phone', 30);
            $table->longText('response');
            $table->longText('others');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('mc_certificate_id')->references('id')->on('mc_certificates');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_mc_answers');
    }
}
