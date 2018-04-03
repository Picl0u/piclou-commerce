<?php

namespace Modules\Vat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Vats extends FormRequest
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
            'name' => 'required',
            'percent' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom de la taxe est obligatoire.',
            'percent.required' => 'Le pourcentage est obligatoire.',
            'percent.numeric' => 'Le pourcentage doit être de format numéric.',
        ];
    }
}
