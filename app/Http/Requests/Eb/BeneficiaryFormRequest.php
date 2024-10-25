<?php

namespace Sibas\Http\Requests\Eb;

use Sibas\Http\Requests\Request;

class BeneficiaryFormRequest extends Request
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
        return [
            'first_name'        => 'required|alpha_space',
            'last_name'         => 'required|alpha_space',
            'mother_last_name'  => 'alpha_space',
            'relationship'      => 'required|alpha_space',
        ];
    }
}
