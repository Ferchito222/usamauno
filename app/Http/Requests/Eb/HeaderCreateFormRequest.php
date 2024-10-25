<?php

namespace Sibas\Http\Requests\Eb;

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
        $document_types = join(',', array_keys(config('base.client_document_types')));
        $genders        = join(',', array_keys(config('base.client_genders')));

        $rules = [
            'code'                => 'alpha_num',
            'first_name'          => 'required|alpha_space',
            'last_name'           => 'required|alpha_space',
            'mother_last_name'    => 'alpha_space',
            'married_name'        => 'alpha_space',
            'document_type'       => 'required|in:' . $document_types,
            'dni'                 => 'required|alpha_dash',
            'complement'          => 'alpha',
            'extension'           => 'required|exists:ad_cities,abbreviation',
            'gender'              => 'required|in:' . $genders,
            'birthdate'           => 'required|date_format:d/m/Y',
            'birth_place'         => 'required|alpha_dash_space',
            'home_address'        => 'required|ands_full',
            'phone_number_home'   => 'required|numeric|digits_between:7,8',
            'phone_number_office' => 'numeric|digits_between:7,8',
        ];

        return $rules;
    }
}
