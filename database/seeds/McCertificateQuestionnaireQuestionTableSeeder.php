<?php

use Sibas\Entities\Mc\CertificateQuestionnaireQuestion;

class McCertificateQuestionnaireQuestionTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new CertificateQuestionnaireQuestion();
    }

    protected function getData()
    {
        $data = [];

        $certificateQuestionnaires = $this->getModelData('CertificateQuestionnaire');
        $questions                 = $this->getModelData('McQuestion');

        foreach ($certificateQuestionnaires as $questionnaire) {
            switch ($questionnaire->id) {
                case 1:
                    array_push($data, [
                        'mc_certificate_questionnaire_id' => $questionnaire->id,
                        'mc_question_id'                  => $questions->first()->id,
                        'active'                          => true,
                    ]);
                    break;
                case 2:
                    foreach ($questions as $key => $question) {
                        if ($question->id > 1 && $question->id < 14) {
                            array_push($data, [
                                'mc_certificate_questionnaire_id' => $questionnaire->id,
                                'mc_question_id'                  => $question->id,
                                'active'                          => true,
                            ]);
                        }
                    }

                    array_push($data, [
                        'mc_certificate_questionnaire_id' => $questionnaire->id,
                        'mc_question_id'                  => $questions->last()->id,
                        'active'                          => true,
                    ]);
                    break;
                case 3:
                    foreach ($questions as $key => $question) {
                        if ($question->id >= 14 && $question->id <= 18) {
                            array_push($data, [
                                'mc_certificate_questionnaire_id' => $questionnaire->id,
                                'mc_question_id'                  => $question->id,
                                'active'                          => true,
                            ]);
                        }
                    }

                    array_push($data, [
                        'mc_certificate_questionnaire_id' => $questionnaire->id,
                        'mc_question_id'                  => $questions->last()->id,
                        'active'                          => true,
                    ]);
                    break;
            }
        }

        return $data;
    }
}
