<?php

namespace Sibas\Http\Requests\Elp;

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
        $payment_methods = join(',', array_keys(config('base.payment_methods')));
        $currencies      = join(',', array_keys(config('base.currencies')));
        $account_types   = join(',', array_keys(config('base.account_types')));

        $rules = [
            'policy_number'  => 'required|exists:ad_policies,number',
            'payment_method' => 'required|in:' . $payment_methods,
        ];

        if ($this->request->get('charge') == 1) {
            $rules['charge_response'] = 'required|alpha_dash_space';
            $rules['charge_period']   = 'required|alpha_dash_space';
        }

        if ($this->request->get('ice') == 1) {
            $rules['number']       = 'required|numeric|exists:op_accounts,number';
            $rules['currency']     = 'required|in:' . $currencies;
            $rules['account_type'] = 'required|in:' . $account_types;
        } else {
            $rules['voucher'] = 'required|alpha_num';
        }

        return $rules;
    }
}
