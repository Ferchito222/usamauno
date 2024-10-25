<?php

namespace Sibas\Http\Requests\Td;

use Sibas\Http\Requests\Request;

class DetailCreateFormRequest extends Request
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
            'nro_folio_real'     => 'required',
            'matter_insured.id'  => 'required|in:' . $property_types,
            'matter_description' => 'required|ands_full',
            'use.id'             => 'required',
            'city.id'            => 'required',
            'zone'               => 'required',
            'locality'           => 'required',
            'address'            => 'required',
        ];
        
        //Remodelacion
        if($this->request->get('state')['id'] == 'P'){
            $rules['construction_value']    = 'required|numeric|min:1';
            $rules['remodeling_value']      = 'required|numeric|min:1';
        
            $monto = $this->request->get('remodeling_value');
            if($this->request->get('currency_value') == 'BS')
                $monto = $this->request->get('remodeling_value')/$this->request->get('exchange');
            
            if($monto>50000)
                $rules['construction_term'] = 'required|numeric|min:1';
            
        }else if($this->request->get('state')['id'] == 'C'){// construccion
            $rules['insured_value']      = 'required|numeric|min:1';
            
            $monto = $this->request->get('insured_value');
            if($this->request->get('currency_value') == 'BS')
                $monto = $this->request->get('insured_value')/$this->request->get('exchange');
            
            if($monto>50000)
                $rules['construction_term'] = 'required|numeric|min:1';
        }else
            $rules['insured_value']      = 'required|numeric|min:1';
            
            
        if($this->request->get('matter_insured')['id'] == 'PR'){
            $rules['state.id']           = 'required';
        }else{
            $rules['number']             = 'required|numeric';
        }

        return $rules;
    }
}
