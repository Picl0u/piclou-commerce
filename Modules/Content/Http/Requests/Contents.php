<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Contents extends FormRequest
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
            'description' => 'required',
            'image' => 'image'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom de la page est obligatoire.',
            'description.required' => 'Le contenu de la page est obligatoire.',
        ];
    }
}
