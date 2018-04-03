<?php

namespace Modules\ShoppingCart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersAdresses extends FormRequest
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
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'country_id' => 'required',
            'phone' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => trans('validation.firstname_required'),
            'lastname.required' => trans('validation.lastname_required'),
            'address.required' => trans('validation.address_required'),
            'zip_code.required' => trans('validation.zip_code_required'),
            'city.required' => trans('validation.city_required'),
            'country_id.required' => trans('validation.country_id_required'),
            'phone.required' => trans('validation.phone_required'),
        ];
    }
}
