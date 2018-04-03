<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Carriers extends FormRequest
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
            'delay' => 'required',
            'image' => 'image'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Le nom du transporteur est obligatoire.",
            'delay.required' => "Le delai de livraison est obligatoire.",
        ];
    }
}
