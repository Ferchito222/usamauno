<?php

namespace Sibas\Http\Requests\Client;

use Sibas\Http\Requests\Request;

class ClientComplementFormRequest extends Request
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
            'first_name'             => 'required|alpha_space',
            'last_name'              => 'required|alpha_space',
            'mother_last_name'       => 'alpha_space',
            'married_name'           => 'alpha_space',
            'birthdate'              => 'required|date_format:d/m/Y',
            'home_address'           => 'required',
            'ad_activity_id'         => 'required|exists:ad_activities,id',
            'occupation_description' => 'required',
            'phone_number_home'      => 'required|numeric|digits_between:7,8',
            'phone_number_mobile'    => 'numeric|digits_between:7,8',
            'phone_number_office'    => 'numeric|digits_between:7,8',
            'email'                  => 'email',
            'gender'                 => 'required',
        ];

        if ($this->request->get('coverage') === 'CO') {
            $rules['percentage_credit'] = 'required|numeric|min:1|max:100';
        }

        if ($this->request->get('credit_product') === '7x5') {
            $rules['involuntary_unemployment'] = 'required|in:1,0';
        }

        return $rules;
    }
}
