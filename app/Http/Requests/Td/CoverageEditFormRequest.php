<?php

namespace Sibas\Http\Requests\Td;

use Sibas\Http\Requests\Request;

class CoverageEditFormRequest extends Request
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
        $term_types = join(',', array_keys(config('base.term_types')));
        $currencies = join(',', array_keys(config('base.currencies')));

        return [
            'validity_start'   => 'required|date_format:d/m/Y',
            'term'             => 'required|integer|min:1',
            'type_term'        => 'required|in:' . $term_types,
            'currency'         => 'required|in:' . $currencies,
            'operation_number' => 'required|integer',
            'policy_number'    => 'required|exists:ad_policies,number',
        ];
    }
}
