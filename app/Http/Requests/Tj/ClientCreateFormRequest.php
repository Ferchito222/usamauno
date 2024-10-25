<?php

namespace Sibas\Http\Requests\Tj;

use Sibas\Http\Requests\Request;

class ClientCreateFormRequest extends Request
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
        $genders         = join(',', array_keys(config('base.client_genders')));
        
        $rules['first_name']                = 'required|alpha_space';
        $rules['last_name']                 = 'required|alpha_space';
        $rules['mother_last_name']          = 'alpha_space';
        $rules['married_name']              = 'alpha_space';
        $rules['gender']                    = 'required|in:' . $genders;
        $rules['dni']                       = 'required|alpha_dash';
        $rules['extension']                 = 'required|exists:ad_cities,abbreviation';
        $rules['birthdate']                 = 'required|date_format:d/m/Y';
        $rules['home_address']              = 'required';
        $rules['ad_activity_id']            = 'required|exists:ad_activities,id';
        $rules['phone_number_home']         = 'numeric|digits_between:7,8';
        $rules['phone_number_mobile']       = 'required|numeric|digits_between:7,8';
        $rules['phone_number_office']       = 'numeric|digits_between:7,8';
        $rules['email']                     = 'required|email';
        $rules['civil_status']              = 'required';
        
        if(!is_null($this->request->get('card'))){
            $rules['card']          = 'required';
            $rules['card_number']   = 'required';
        }
        
        return $rules;
    }
}
