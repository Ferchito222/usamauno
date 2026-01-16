<?php

namespace Sibas\Http\Requests\Vd;

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
        $hands           = join(',', array_keys(config('base.client_hands')));
        
        $rules['home_address']              = 'required';
        $rules['phone_number_home']         = 'numeric|digits_between:7,8';
        $rules['ad_activity_id']            = 'required|exists:ad_activities,id';
        $rules['occupation_description']    = 'required';
        $rules['phone_number_mobile']       = 'numeric|digits_between:7,8';
        $rules['email']                     = 'email';
        
        $rules['workplace']                 = 'alpha_space';
        $rules['height']                    = 'integer';
        $rules['weight']                    = 'integer';
        $rules['position']                  = 'alpha_space';
        $rules['name_spouse']               = 'alpha_space';
        $rules['personal_reference']        = 'alpha_space';
        
        if(!is_null($this->request->get('ad_plan_id')))
            $rules['ad_plan_id'] = 'required';
        
        return $rules;
    }
}