<?php

namespace Sibas\Http\Requests\Td;

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
        $genders    = join(',', array_keys(config('base.client_genders')));
        $term_types = join(',', array_keys(config('base.term_types')));
        $currencies = join(',', array_keys(config('base.currencies')));
        
        if($this->request->get('type') == 'N'){
            $rules = [
                'last_name'              => 'required|alpha_space',
                'mother_last_name'       => 'alpha_space',
                'document_type'          => 'required',
                'country'                => 'required',
                'zone'                   => 'required',
                'gender'                 => 'required',
                'locality'               => 'alpha_space',
                'mother_last_name'       => 'alpha_space',
                'married_name'           => 'alpha_space',
                'phone_number_office'    => 'numeric',
            ];
        }
        
        $rules['first_name']                = 'required|alpha_space';
        $rules['dni']                       = 'required|numeric';
        $rules['extension']                 = 'required|exists:ad_cities,abbreviation';
        $rules['birthdate']                 = 'required|date_format:d/m/Y';
        $rules['home_address']              = 'required';
        $rules['ad_activity_id']            = 'required|exists:ad_activities,id';
        $rules['occupation_description']    = 'required';
        $rules['phone_number_home']         = 'required|numeric|digits_between:7,8';
        $rules['phone_number_mobile']       = 'numeric|digits_between:7,8';
        $rules['email']                     = 'email';
        $rules['validity_start']            = 'required|date_format:d/m/Y';
        $rules['term']                      = 'required|integer|min:1';
        $rules['type_term']                 = 'required|in:' . $term_types;
        
        if($this->request->get('validity_start') != ''){
            $date = \Carbon\Carbon::now();
            $date2 = \Carbon\Carbon::createFromFormat('d/m/Y', $this->request->get('validity_start'));

            if($date2 < $date)
                $rules['validity_start_five'] = 'required';
        }
        
        return $rules;
    }
}