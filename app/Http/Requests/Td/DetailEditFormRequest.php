<?php

namespace Sibas\Http\Requests\Td;

use Sibas\Http\Requests\Request;

class DetailEditFormRequest extends Request
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
            'zone'               => 'required|ands_full',
            'locality'           => 'required|ands_full',
            'address'            => 'required|ands_full',
        ];
        if($this->request->get('matter_insured') != 'PR'){
            $rules['make']              = 'required|ands_full';
            $rules['model']             = 'required|ands_full';
            $rules['chassis_number']    = 'required|ands_full';
        }
       
        return $rules;
    }
}
