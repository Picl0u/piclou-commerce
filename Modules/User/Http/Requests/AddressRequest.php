<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
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

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required' => __('validation.firstname_required'),
            'lastname.required' => __('validation.lastname_required'),
            'address.required' => __('validation.address_required'),
            'zip_code.required' => __('validation.zip_code_required'),
            'city.required' => __('validation.city_required'),
            'country_id.required' => __('validation.country_id_required'),
            'phone.required' => __('validation.phone_required'),
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
