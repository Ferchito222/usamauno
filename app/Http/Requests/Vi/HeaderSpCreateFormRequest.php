<?php

namespace Sibas\Http\Requests\Vi;

use Sibas\Http\Requests\Request;

class HeaderSpCreateFormRequest extends Request
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
        $payment_methods = join(',', array_keys(config('base.payment_methods')));
        $periods         = join(',', array_keys(config('base.periods')));

        $rules = [
            'qs'               => 'required|array',
            // 'numberBN'         => 'required|numeric|min:1',
            'beneficiaries'    => 'required|array',
            'insured_value'    => 'required|numeric',
            'payment_method'   => 'required|in:' . $payment_methods,
            'period'           => 'required|in:' . $periods,
            'account_number'   => 'required|numeric',
            'credit_card'      => 'numeric',
            'plan'             => 'required|exists:ad_plans,id',
            'home_number'      => 'numeric',
            'business_address' => 'ands_full',
            'taker_name'       => 'required|alpha_space',
            'taker_dni'        => 'required|alpha_dash',
        ];

        if ($this->request->has('beneficiaries')) {
            $beneficiaries = $this->request->get('beneficiaries');

            foreach ($beneficiaries as $key => $beneficiary) {
                $rules['beneficiaries.' . $key . '.first_name']       = 'required|alpha_space';
                $rules['beneficiaries.' . $key . '.last_name']        = 'required|alpha_space';
                $rules['beneficiaries.' . $key . '.mother_last_name'] = 'alpha_space';
                $rules['beneficiaries.' . $key . '.relationship']     = 'required|alpha_space';
                $rules['beneficiaries.' . $key . '.dni']              = 'alpha_dash';
                $rules['beneficiaries.' . $key . '.participation']    = 'required|numeric|min:1|max:100';
            }
        }

        return $rules;
    }

}
