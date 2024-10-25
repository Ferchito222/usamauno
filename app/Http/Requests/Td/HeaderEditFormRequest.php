<?php

namespace Sibas\Http\Requests\Td;

use Sibas\Http\Requests\Request;

class HeaderEditFormRequest extends Request
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
            'policy_number'     => 'required|exists:ad_policies,number',
            'bill_name'         => 'required',
            'bill_dni'          => 'required',
            'operation_number'  => 'required',
            'bill_complement'   => 'digits:3',
        ];

        return $rules;
    }
}
