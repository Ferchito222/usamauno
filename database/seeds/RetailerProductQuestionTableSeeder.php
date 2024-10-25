<?php

use Sibas\Entities\RetailerProductQuestion;

class RetailerProductQuestionTableSeeder extends BaseSeeder
{

    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new RetailerProductQuestion();
    }


    protected function getData()
    {
        $data = [ ];

        $questions        = $this->getModelData('Question')->all();
        $retailerProducts = $this->getModelData('RetailerProduct');

        foreach ($questions as $key => $question) {
            $response = $question->id == 5 ? true : false;

            if ($key <= 5) {
                $rp_id = $retailerProducts->first()->id;
            } else {
                $rp_id = $retailerProducts->last()->id;
            }

            $data[] = [
                'ad_retailer_product_id' => $rp_id,
                'ad_question_id'         => $question->id,
                'order'                  => ( $key <= 5 ? $key + 1 : $key - 5 ),
                'response'               => $response,
                'active'                 => true,
            ];
        }

        return $data;
    }
}
