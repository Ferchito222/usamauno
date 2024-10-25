<?php

namespace Sibas\Http\Requests\De;

use Sibas\Http\Requests\Request;

class BalanceFormRequest extends Request
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
            'amount_requested' => 'required|numeric|min:1',
            'balance'          => 'required|numeric|min:0',
            'cumulus'          => 'required|numeric|min:0',
            'refinancing'      => 'required|numeric|min:0',
            'parallel_credit'  => 'required|numeric|min:0',
        ];

        $cumulus_rule = '';

        if ($this->request->has('movement_type') && $this->request->get('movement_type') === 'LC') {
            $cumulus             = $this->request->get('cumulus');
            $amount_requested_bs = $this->request->get('amount_requested_bs');
            $balance             = $this->request->get('balance');

            if ($amount_requested_bs > $cumulus) {
                $cumulus_rule = '|min:' . $amount_requested_bs;
            }

            if ($balance > $cumulus) {
                $cumulus_rule = '|min:' . $balance;
            }

            $rules['cumulus'] .= $cumulus_rule;
        }

        return $rules;
    }
}
