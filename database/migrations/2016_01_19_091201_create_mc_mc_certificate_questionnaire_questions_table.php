<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMcMcCertificateQuestionnaireQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_certificate_questionnaire_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mc_certificate_questionnaire_id')->unsigned();
            $table->integer('mc_question_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('mc_certificate_questionnaire_id', 'mcq_id_foreign')->references('id')->on('mc_certificate_questionnaires');
            $table->foreign('mc_question_id')->references('id')->on('mc_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mc_certificate_questionnaire_questions');
    }
}
