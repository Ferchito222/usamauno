<?php

namespace Sibas\Http\Requests\De;

use Sibas\Http\Requests\Request;

class CreditLineCreateFormRequest extends Request
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
        $coverages = join(',', array_keys(config('base.credit_line.coverages')));

        $rules = [
            'numberLine' => 'required|numeric',
            'coverage'   => 'required|in:' . $coverages,
            'policyType' => 'required|in:TE,TN,CA',
        ];

        if (in_array($this->request->get('coverage'), ['IC', 'MC'])) {
            $clients = $this->request->get('clients');

            foreach ($clients as $key => $client) {
                $rules['clients.' . $key . '.dni']              = 'required|alpha_dash';
                $rules['clients.' . $key . '.complement']       = 'alpha_num';
                $rules['clients.' . $key . '.number']           = 'required|integer';
                $rules['clients.' . $key . '.percentage']       = 'required|numeric';
                $rules['clients.' . $key . '.percentageCredit'] = 'required|numeric';
            }
        }

        return $rules;
    }
}
