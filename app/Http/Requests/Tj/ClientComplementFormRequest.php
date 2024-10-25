<?php

namespace Sibas\Http\Requests\Tj;

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
        $rules['ad_activity_id']            = 'required|exists:ad_activities,id';
        $rules['phone_number_mobile']       = 'numeric|digits_between:7,8';
        $rules['phone_number_home']         = 'numeric|digits_between:7,8';
        $rules['phone_number_mobile']       = 'numeric|digits_between:7,8';
        $rules['phone_number_office']       = 'numeric|digits_between:7,8';
        $rules['email']                     = 'email';
        $rules['workplace']                 = 'alpha_space';
        $rules['height']                    = 'integer';
        $rules['weight']                    = 'integer';
        
        return $rules;
    }
}
