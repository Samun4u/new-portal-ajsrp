<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust the authorization logic if needed
    }

    public function rules()
    {
        $rules = [
            'client_name' => 'bail|required',
            'email' => 'required|email',
            'description' => 'bail|required',
            'discount' => 'bail|required',
            'expire_date' => 'bail|required|date|after_or_equal:today',
            'address' => 'bail|required',
            'service_id.*'   => 'required',
            'price.*'        => 'required|numeric',
            'quantity.*'     => 'required|integer',
            'duration.*'     => 'required|string',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'expire_date.date_format' => 'Please select a date',
            'service_id.*.required' => 'The service name field is required.',
            'price.*.required' => 'The price field is required.',
            'price.*.numeric'  => 'The price must be a number.',
            'quantity.*.required' => 'The quantity field is required.',
            'quantity.*.integer'  => 'The quantity must be an integer.',
            'duration.*.required' => 'The duration field is required.',
        ];
    }
}
