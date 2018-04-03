<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Products extends FormRequest
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
            'image' => 'image',
            'shop_category_id' => 'required|integer',
            'reference' => 'required',
            'price_ht' => 'required|numeric',
            'price_ttc' => 'required|numeric'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'description.required' => 'La description du produit est obligatoire.',
            'shop_category_id.required' => 'La catégorie principale est obligatoire.',
            'reference.required' => 'La référence du produit est obligatoire.',
            'price_ht.required' => 'La prix HT du produit est obligatoire.',
            'price_ht.numeric' => 'La prix HT du produit doit être un nombre.',
            'price_ttc.required' => 'La prix TTC du produit est obligatoire.',
            'price_ttc.numeric' => 'La prix TTC du produit doit être un nombre.',
        ];
    }
}
