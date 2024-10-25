<?php

use Sibas\Entities\Question;

class QuestionTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Question();
    }

    protected function getData()
    {
        $data = [];

        $questions = [
            '¿Ha recibido tratamiento médico, o le han recomendado exámenes para diagnóstico, o tomó medicinas'
                . 'recetadas o recomendadas por un médico como consecuencia de la presencia o sospecha de alguna'
                . 'enfermedad grave?',
            '¿Tiene o ha tenido algún tipo de cáncer o problemas o enfermedades cardiacas?',
            '¿Tiene SIDA o es portador del VIH?',
            '¿Durante los últimos cinco (5) años, ha estado en el hospital o cualquier otro establecimiento médico o'
                . 'ha estado incapacitado para asistir al trabajo o realizar las actividades normales de la vida diaria?',
            '¿Se encuentra usted actualmente en buen estado de salud?',
            '¿Sufre usted de alguna invalidez significativa?',
            'Cancer',
            'Diabetes',
            'Insuficiencia Renal',
            'SIDA',
            'Enfermedades del Corazon',
            'Enfermedades Cerebro Vasculares',
        ];

        foreach ($questions as $question) {
            $data[] = [
                'question' => $question
            ];
        }

        return $data;
    }
}
