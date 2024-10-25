<?php

namespace Sibas\Http\Requests\Eb;

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

        /*if ($this->request->get('ice') == 0) {
            $rules['voucher'] = 'required|alpha_num';
        }*/

        return $rules;
    }
}
