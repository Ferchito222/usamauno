<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMcCertificateQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_certificate_questionnaires', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mc_certificate_id')->unsigned();
            $table->integer('mc_questionnaire_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('mc_certificate_id')->references('id')->on('mc_certificates');
            $table->foreign('mc_questionnaire_id')->references('id')->on('mc_questionnaires');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mc_certificate_questionnaires');
    }
}
