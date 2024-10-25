<?php

namespace Sibas\Http\Requests\Td;

use Sibas\Http\Requests\Request;

class CoverageCreateFormRequest extends Request
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
            'rp_de'        => 'required|string',
            'client'       => 'required',
            'currency.id'  => 'required|in:' . $currencies,
            'term'         => 'required|integer|min:1',
            'type_term.id' => 'required|in:' . $term_types,
        ];
    }
}
