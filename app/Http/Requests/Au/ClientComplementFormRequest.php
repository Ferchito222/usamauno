<?php

namespace Sibas\Http\Requests\Au;

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
        $civil_status = join(',', array_keys(config('base.client_civil_status')));
        $genders      = join(',', array_keys(config('base.client_genders')));
        if($this->request->get('type') == 'N'){
            $rules = [
                'last_name'              => 'required|alpha_space',
                'mother_last_name'       => 'alpha_space',
                'married_name'           => 'alpha_space',
                'civil_status'           => 'required|in:' . $civil_status,
                'country'                => 'alpha_space',
                'birthdate'              => 'required|date_format:d/m/Y',
                'home_number'            => 'required|numeric',
                'gender'                 => 'required|in:' . $genders,
            ];
        }
        $rules['first_name']                = 'required|alpha_space';
        $rules['home_address']              = 'required|ands_full';
        $rules['ad_activity_id']            = 'required|exists:ad_activities,id';
        $rules['occupation_description']    = 'required';
        $rules['phone_number_home']         = 'required|numeric|digits_between:7,8';
        $rules['phone_number_mobile']       = 'numeric|digits_between:7,8';
        $rules['email']                     = 'email';
        
        return $rules;
    }
}
