<?php

namespace Sibas\Http\Requests\De;

use Sibas\Http\Requests\Request;

class HeaderResultFormRequest extends Request
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
        return [
            'rate_id' => 'required|exists:ad_rates,id',
        ];
    }

    public function getValidatorInstance()
    {
        $input = $this->request->all();
        $input['rate_id'] = decrypt($input['rate_id']);

        $this->request->replace($input);

        return parent::getValidatorInstance();
    }
}
