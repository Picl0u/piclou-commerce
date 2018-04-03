<?php

namespace Modules\ShoppingCart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpressUsers extends FormRequest
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
            'express_firstname' => 'required',
            'express_lastname' => 'required',
            'express_email' => 'required|email'
        ];
    }

    public function messages()
    {
        return [
            'express_firstname.required' => trans('validation.firstname_required'),
            'express_lastname.required' => trans('validation.lastname_required'),
            'express_email.required' => trans('validation.email_required'),
            'express_email.email' => trans('validation.email_format'),
        ];
    }
}
