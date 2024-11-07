<?php

namespace Sibas\Http\Requests\Client;

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
        $rules = [
            'first_name'        => 'required|alpha_space',
            'married_name'      => 'alpha_space',
            'dni'               => 'required|alpha_dash',
            'complement'        => 'alpha_num',
            'extension'         => 'required|exists:ad_cities,abbreviation',
            'birthdate'         => 'required|date_format:d/m/Y',
            'phone_number_home' => 'required|numeric|digits_between:7,8',
            'email'             => 'email',
            'weight'            => 'required|numeric|min:1|digits_between:1,3',
            'height'            => 'required|numeric|min:1|digits_between:1,3',
            'gender'            => 'required',
        ];

        if (strpos($this->route()->getPath(), 'edit') === false) {
            $rules['debit_balance'] = 'required|numeric';
            $rules['number']        = 'required';
            $rules['addresses']     = 'required';
            $rules['operations']    = 'required';
        }

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

        if ($this->request->get('coverage') === 'CO') {
            $rules['percentage_credit'] = 'required|numeric|min:1|max:100';
        }

        if (auth()->user()->profile->first()->slug !== 'GUP') {
            $rules['home_address']           = 'required';
            $rules['ad_activity_id']         = 'required|exists:ad_activities,id';
            $rules['occupation_description'] = 'required';
            $rules['phone_number_mobile']    = 'numeric|digits_between:7,8';
            $rules['phone_number_office']    = 'numeric|digits_between:7,8';

            if ($this->request->get('credit_product') === '7x5') {
                $rules['involuntary_unemployment'] = 'required|in:1,0';
            }
        }

        //if($this->request->get('economic_activity_type_code') == 2 && $this->request->get('credit_product') != 'PCA'){
        if(true){
            $rules['cesantia'] = 'required';
            if($this->request->get('cesantia')==1){
                $rules['company_work'] = 'required';
                $rules['date_entry'] = 'required|date_format:d/m/Y';
                $rules['type_of_contract'] = 'required';
    
                if($this->request->get('public_work') == 1){
                    $rules['position'] = 'required';
                    $rules['period'] = 'required';
                    $rules['institution'] = 'required';
                }
            }
        }

        return $rules;
    }

}
