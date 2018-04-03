<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceExport extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_begin' => 'date',
            'date_end' => 'date',
        ];
    }

    public function messages()
    {
        return [
          'date_begin.date' => 'La date de début doit être une date.',
          'date_end.date' => 'La date de fin doit être une date.'
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
