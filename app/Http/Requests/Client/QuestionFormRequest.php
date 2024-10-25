<?php

namespace Sibas\Http\Requests\Client;

use Sibas\Http\Requests\Request;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class QuestionFormRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'qs_number' => 'required|numeric|min:0'
        ];

        $data = $this->request->all();

        foreach ($data['qs'] as $key => $items) {
            $rules['qs.' . $key . '.response'] = 'required|in:1,0';

            if (key_exists('response', $items)) {
                if ($items['expected'] != $items['response']) {
                    if ($this->request->has('credit_product')) {
                        if (key_exists('response_specification', $items)) {
                            $rules['qs.' . $key . '.response_specification'] = 'required';
                        }
                    } else {
                        $rules['qs_observation'] = 'required';
                    }
                }
            }
        }

        return $rules;
    }
}
