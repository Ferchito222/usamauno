<?php

namespace Sibas\Http\Requests\De;

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
        $currencies     = join(',', array_keys(config('base.currencies')));
        $term_types     = join(',', array_keys(config('base.term_types')));

        $rules = [
            'coverage'         => 'required|exists:ad_coverages,id',
            'amount_requested' => 'required|numeric|min:1',
            'currency'         => 'required|in:' . $currencies,
            'term'             => 'required|integer|min:1',
            'type_term'        => 'required|in:' . $term_types,
            'credit_product'   => 'required|exists:ad_credit_products,id',
            'policy_number'    => 'required|exists:ad_policies,number',
            'type_of_request'  => 'required',
        ];
        if(request()->get('credit_product') == 4)
            $rules['card_number'] = 'required';
        else
            $rules['operation_number'] = 'required|numeric';
            
        if (stripos($this->user()->city->slug, 'nacional') !== false) {
            $rules['place_issuance'] = 'required|exists:ad_cities,id';
        }

        return $rules;
    }
}
