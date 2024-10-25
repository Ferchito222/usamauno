<?php

namespace Sibas\Http\Requests\Tj;

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
            'bill_name'         => 'required',
            'bill_dni'          => 'required',
            'policy_number'    => 'required',
        ];
        
        if(base64_decode($this->request->get('card')) == 'CC'){
            $rules['account_number'] = 'required|numeric';
        }
        
        if(base64_decode($this->request->get('card')) == 'DC'){
            $rules['savings_bank'] = 'required|numeric';
        }
        
        return $rules;
    }
}
