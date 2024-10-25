<?php

namespace Sibas\Http\Requests\Elp;

use Sibas\Http\Requests\Request;

class ClientEditFormRequest extends Request
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
        $avenue_street  = join(',', array_keys(config('base.avenue_street')));

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
            'country'                => 'required|alpha_space',
            'gender'                 => 'required|in:' . $genders,
            'birthdate'              => 'required|date_format:d/m/Y',
            'birth_place'            => 'alpha_dash_space',
            'place_residence'        => 'required|exists:ad_cities,slug',
            'locality'               => 'required|alpha_dash_space',
            'home_address'           => 'required|ands_full',
            'home_number'            => 'required|numeric',
            'avenue_street'          => 'required|in:' . $avenue_street,
            'ad_activity_id'         => 'required|exists:ad_activities,id',
            'occupation_description' => 'required|ands_full',
            'business_address'       => 'ands_full',
            'phone_number_home'      => 'required|numeric|digits_between:7,8',
            'phone_number_mobile'    => 'numeric|digits_between:7,8',
            'email'                  => 'email',
        ];

        return $rules;
    }
}
