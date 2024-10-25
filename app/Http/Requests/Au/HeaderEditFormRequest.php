<?php

namespace Sibas\Http\Requests\Au;

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
            'policy_number' => 'required|exists:ad_policies,number',
        ];

        if ($this->request->get('warranty') == 1) {
            $rules['operation_number'] = 'required|integer';
        }

        return $rules;
    }
}
