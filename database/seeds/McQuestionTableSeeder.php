<?php

use Sibas\Entities\Mc\McQuestion;

class McQuestionTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new McQuestion();
    }

    protected function getData()
    {
        $data = [
            [
                'question' => 'Cuestionario medico especifico',
                'type'     => 'CB',
            ],
            [
                'question' => 'Hemograma',
                'type'     => 'CB',
            ],
            [
                'question' => 'Examen General de Orina (Fisico Quimico y Microscopico)',
                'type'     => 'CB',
            ],
            [
                'question' => 'HIV',
                'type'     => 'CB',
            ],
            [
                'question' => 'Bilirubina Total',
                'type'     => 'CB',
            ],
            [
                'question' => 'Colesterol Total, HDL y LDL',
                'type'     => 'CB',
            ],
            [
                'question' => 'Glicemia',
                'type'     => 'CB',
            ],
            [
                'question' => 'Trigliceridos',
                'type'     => 'CB',
            ],
            [
                'question' => 'Urea',
                'type'     => 'CB',
            ],
            [
                'question' => 'Acido Urico',
                'type'     => 'CB',
            ],
            [
                'question' => 'Creatinina',
                'type'     => 'CB',
            ],
            [
                'question' => 'Dosificacion GAMA GT y transaminasas (SGOT y SGPT), Serologia Hepatitis B y C',
                'type'     => 'CB',
            ],
            [
                'question' => 'P.S.A. Hombres CA - 125 Mujeres',
                'type'     => 'CB',
            ],
            [
                'question' => 'Electrocardiograma',
                'type'     => 'CB',
            ],
            [
                'question' => 'Radiografia de Torax',
                'type'     => 'CB',
            ],
            [
                'question' => 'Test de Esfuerzo',
                'type'     => 'CB',
            ],
            [
                'question' => 'Informe Cardiovascular',
                'type'     => 'CB',
            ],
            [
                'question' => 'Examen Medico Cardiologico',
                'type'     => 'CB',
            ],
            [
                'question' => 'Otros',
                'type'     => 'TX',
            ],
        ];

        return $data;
    }
}
