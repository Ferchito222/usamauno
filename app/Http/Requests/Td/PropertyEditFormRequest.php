<?php

namespace Sibas\Http\Requests\Td;

use Sibas\Http\Requests\Request;

class PropertyEditFormRequest extends Request
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
        $property_types = join(',', array_keys(config('base.property_types')));
        $property_uses  = join(',', array_keys(config('base.property_uses')));

        $rules = [
            'matter_insured'     => 'required|in:' . $property_types,
            'matter_description' => 'required|ands_full',
            'number'             => 'required|numeric',
            'property_use'       => 'required|in:' . $property_uses,
            'city'               => 'required|exists:ad_cities,slug',
            'zone'               => 'required|ands_full',
            'locality'           => 'required|ands_full',
            'address'            => 'required|ands_full',
            'insured_value'      => 'required|numeric|min:1',
        ];

        if ($this->request->get('matter_insured') === 'PR') {
            $rules['land_value'] = 'required|numeric|min:1';
        }

        return $rules;
    }
}
