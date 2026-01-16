<?php

namespace Sibas\Http\Requests\Vd;

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
        $hands           = join(',', array_keys(config('base.client_hands')));
        
        $rules['first_name']                = 'required|alpha_space';        
        $rules['married_name']              = 'alpha_space';
        $rules['gender']                    = 'required|in:' . $genders;
        $rules['dni']                       = 'required|numeric';
        $rules['complement']                = 'alpha_dash';
        //$rules['extension']                 = 'required|exists:ad_cities,abbreviation';
        $rules['birthdate']                 = 'required|date_format:d/m/Y';
        $rules['birth_place']               = 'required';
        $rules['home_address']              = 'required';
        $rules['phone_number_home']         = 'required|numeric|digits_between:7,8';
        $rules['ad_activity_id']            = 'required|exists:ad_activities,id';
        $rules['occupation_description']    = 'required';
        $rules['country']                   = 'required|alpha_space';
        // $rules['weight']                    = 'required|numeric';
        // $rules['height']                    = 'required|numeric';
        // $rules['pressure']                  = 'required';
        
        $rules['phone_number_mobile']       = 'numeric|digits_between:7,8';
        $rules['email']                     = 'email';

        if (!$this->request->get('last_name') && !$this->request->get('mother_last_name')){
            $rules['last_name']                 = 'required|alpha_space';
            $rules['mother_last_name']          = 'required|alpha_space';
        }

        // if(!is_null($this->request->get('ad_plan_id')))
            // $rules['ad_plan_id'] = 'required';
        
        
        // if($this->request->get('edition') == 0)
            // $rules['ad_plan_id'] = 'required';
        
        return $rules;
    }
}