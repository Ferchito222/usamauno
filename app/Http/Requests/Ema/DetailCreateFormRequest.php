<?php

namespace Sibas\Http\Requests\Ema;

use Sibas\Http\Requests\Request;

class DetailCreateFormRequest extends Request
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
            'description'   => 'required',
            'serial_number' => 'required',
            'city'          => 'required',
            'insured_value' => 'required|numeric|min:1',
        ];

        return $rules;
    }
}
