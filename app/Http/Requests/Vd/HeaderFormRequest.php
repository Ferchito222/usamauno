<?php

namespace Sibas\Http\Requests\Vd;

use Sibas\Http\Requests\Request;

class HeaderFormRequest extends Request
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
            'bill_name'         => 'required',
            'bill_dni'          => 'required',
            'payment_method'    => 'required',
            'period'            => 'required',
            'account_number'    => 'numeric',
            'card_number'       => 'numeric',
        ];
        
        $period = config('base.periods.'.$this->request->get('period'));
        
        if($this->request->get('payment_method') == 'CO'){    
            if($period != config('base.periods.Y') && $period != config('base.periods.S'))
                $rules['period'] = 'required|in:'.config('base.periods.Y').','.config('base.periods.S');
            
            if($period == config('base.periods.Y'))
                $rules['term'] = 'required|numeric|max:120|min:12';
            else
                $rules['term'] = 'required|numeric|max:120|min:6';
        }
        
        if($this->request->get('payment_method') == 'DA'){
            
            if(!in_array($period, [config('base.periods.Y'),config('base.periods.M')]))
                $rules['period'] = 'required|in:'.config('base.periods.Y').','.config('base.periods.M');
            
            if($this->request->get('period') == 'M')
                $rules['term'] = 'required|numeric|in:12';
            
            if($this->request->get('period') == 'Y')
                $rules['term'] = 'required|numeric|max:120|min:12';
            
            $rules['account_number'] = 'required|numeric';
        }
        
        if($this->request->get('payment_method') == 'CR'){    
            if($period != config('base.periods.Y'))
                $rules['period'] = 'required|in:'.config('base.periods.Y');
            
            $rules['term']              = 'required|numeric|max:120|min:12';
            $rules['case_number']       = 'required|numeric';
            $rules['account_number']    = 'required|numeric';
        }
        
        if($this->request->get('payment_method') == 'TJ'){
            if(!in_array($period, [config('base.periods.Y'),config('base.periods.M')]))
                $rules['period'] = 'required|in:'.config('base.periods.Y').', '.config('base.periods.M');
            
            $rules['term'] = 'required|numeric|max:120|min:12';
            $rules['expiration_card_month'] = 'required';
            $rules['expiration_card_year']  = 'required';
            $rules['card_number']           = 'required|numeric';
        }
        

        return $rules;
    }
}