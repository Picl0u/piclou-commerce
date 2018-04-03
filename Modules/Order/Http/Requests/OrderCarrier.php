<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCarrier extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shipping_url' => 'required|url',
            'shipping_order_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'shipping_url.required' => "L'URL de suivis est obligatoire.",
            'shipping_order_id' => "l'ID du suivis est obligatoire",
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


}
