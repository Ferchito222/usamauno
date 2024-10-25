<?php

namespace Sibas\Http\Requests\Td;

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
        $civil_status  = join(',', array_keys(config('base.client_civil_status')));
        $genders       = join(',', array_keys(config('base.client_genders')));
        $avenue_street = join(',', array_keys(config('base.avenue_street')));

        if($this->request->get('type') == 'N'){
            $rules = [
                'last_name'              => 'required|alpha_space',
                'country'                => 'required',
                'zone'                   => 'required',
                'civil_status'           => 'required|in:' . $civil_status,
                'home_number'            => 'required|numeric',
                'avenue_street'          => 'required|in:' . $avenue_street,
                'mother_last_name'       => 'alpha_space',
                'married_name'           => 'alpha_space', 
            ];
        }
        
        $rules['first_name']                = 'required|alpha_space';
        $rules['birthdate']                 = 'required|date_format:d/m/Y';
        $rules['home_address']              = 'required';
        $rules['ad_activity_id']            = 'required|exists:ad_activities,id';
        $rules['occupation_description']    = 'required';
        $rules['phone_number_home']         = 'required|numeric|digits_between:7,8';
        $rules['phone_number_mobile']       = 'numeric|digits:8';
        $rules['email']                     = 'email';
        
        return $rules;
    }
}
