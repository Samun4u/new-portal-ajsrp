<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ClientOrderRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'client_id' => 'bail|required',
            'service_id.*' => 'bail|required',
            'price.*' => 'required|numeric',
            'quantity.*' => 'required|integer',
            'discount.*' => 'required|string',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'client_id.required' => 'The client name field is required.',
            'service_id.*.required' => 'The service name field is required.',
            'price.*.required' => 'The price field is required.',
            'price.*.numeric' => 'The price must be a number.',
            'quantity.*.required' => 'The quantity field is required.',
            'quantity.*.integer' => 'The quantity must be an integer.',
            'discount.*.required' => 'The discount field is required.',
        ];
    }

}
