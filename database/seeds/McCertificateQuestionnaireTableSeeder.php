<?php

use Sibas\Entities\Mc\CertificateQuestionnaire;

class McCertificateQuestionnaireTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new CertificateQuestionnaire();
    }

    protected function getData()
    {
        $data = [];

        $certificate = $this->getModelData('Certificate')->first();

        $questionnaires = $this->getModelData('Questionnaire');

        foreach ($questionnaires as $questionnaire) {
            array_push($data, [
                'mc_certificate_id'   => $certificate->id,
                'mc_questionnaire_id' => $questionnaire->id,
                'active'              => true,
            ]);
        }

        return $data;
    }
}
