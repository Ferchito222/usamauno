<?php

namespace Sibas\Http\Requests\Elp;

use Sibas\Http\Requests\Request;

class HeaderCreateFormRequest extends Request
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
        $civil_status   = join(',', array_keys(config('base.client_civil_status')));
        $document_types = join(',', array_keys(config('base.client_document_types')));
        $genders        = join(',', array_keys(config('base.client_genders')));
        $account_types  = join(',', array_keys(config('base.account_types')));
        $currencies     = join(',', array_keys(config('base.currencies')));

        $rules = [
            'first_name'             => 'required|alpha_space',
            'last_name'              => 'required|alpha_space',
            'mother_last_name'       => 'alpha_space',
            'married_name'           => 'alpha_space',
            'civil_status'           => 'required|in:' . $civil_status,
            'document_type'          => 'required|in:' . $document_types,
            'dni'                    => 'required|alpha_dash',
            'complement'             => 'alpha',
            'extension'              => 'required|exists:ad_cities,abbreviation',
            'country'                => 'alpha_space',
            'gender'                 => 'in:' . $genders,
            'birthdate'              => 'required|date_format:d/m/Y',
            'birth_place'            => 'alpha_dash_space',
            'place_residence'        => 'exists:ad_cities,slug',
            'locality'               => 'alpha_dash_space',
            'home_address'           => 'ands_full',
            'ad_activity_id'         => 'required|exists:ad_activities,id',
            'occupation_description' => 'ands_full',
            'phone_number_home'      => 'required|numeric|digits_between:7,8',
            'phone_number_mobile'    => 'numeric|digits_between:7,8',
            'email'                  => 'email',
        ];

        if ( ! empty( $this->request->get('client_eco') )) {
            $rules['code']             = 'required';
            $rules['account.type']     = 'required|in:' . $account_types;
            $rules['account.currency'] = 'required|in:' . $currencies;
            $rules['account.number']   = 'required';
            $rules['account.total']    = 'required';
        }

        if ($this->request->get('reference_id') === 'other') {
            $rules['reference_other'] = 'required|alpha_space';
        } else {
            $rules['reference_id'] = 'required|exists:ad_cashiers,slug';
        }

        return $rules;
    }
}
