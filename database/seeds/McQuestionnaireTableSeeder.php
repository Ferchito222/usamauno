<?php

use Sibas\Entities\Mc\Questionnaire;

class McQuestionnaireTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Questionnaire();
    }

    protected function getData()
    {
        $data = [
            [
                'title' => 'CUESTIONARIO MEDICO ESPECIFICO'
            ],
            [
                'title' => 'EXAMENES DE LABORATORIO'
            ],
            [
                'title' => 'EXAMENES MEDICOS DE ATENCION'
            ],
        ];

        return $data;
    }
}
