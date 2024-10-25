<?php

namespace Sibas\Http\Requests\De;

use Sibas\Http\Requests\Request;

class BeneficiaryDeFormRequest extends Request
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
        $type = join(',', array_keys(config('base.beneficiary_coverages')));

        $rules = [
            'type'         => 'required|in:' . $type,
            'first_name'   => 'required|alpha_space',
            'dni'          => 'alpha_dash',
            'complement'   => 'alpha_num',
            'extension'    => 'exists:ad_cities,abbreviation',
            'age'          => 'numeric',
            'relationship' => 'required|alpha_space',
        ];

        if (empty($this->request->get('last_name')) && empty($this->request->get('mother_last_name'))) {
            $rules['any_last_name'] = 'required';
        } else {
            if (! empty($this->request->get('last_name'))) {
                $rules['last_name']        = 'required|alpha_space';
                $rules['mother_last_name'] = 'alpha_space';
            } elseif (! empty($this->request->get('mother_last_name'))) {
                $rules['last_name']        = 'alpha_space';
                $rules['mother_last_name'] = 'required|alpha_space';
            }
        }

        return $rules;
    }
}
