<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnDeleteToMcCertificateQuestionnaireQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE mc_certificate_questionnaire_questions
                          DROP FOREIGN KEY mcq_id_foreign;');

        DB::statement('ALTER TABLE mc_certificate_questionnaire_questions
                          ADD CONSTRAINT mcq_id_foreign
                        FOREIGN KEY (mc_certificate_questionnaire_id)
                        REFERENCES mc_certificate_questionnaires (id)
                          ON DELETE CASCADE
                          ON UPDATE NO ACTION;');

        Schema::table('mc_certificate_questionnaire_questions', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mc_certificate_questionnaire_questions', function (Blueprint $table) {
            //
        });
    }
}
