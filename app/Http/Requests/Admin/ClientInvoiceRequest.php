<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ClientInvoiceRequest extends FormRequest
{
    public function rules(){
        $rules = [
            'client_id' => 'required',
            'due_date' => 'bail|required|date|after_or_equal:today',
        ];

        if(!is_null($this->order_id)){
            $rules['payable_amount'] = 'bail|required';
        }
        if(is_null($this->payable_amount)){
            $rules['service_id.*'] = 'required';
            $rules['price.*']      = 'required|numeric';
            $rules['quantity.*']   = 'required|integer';
            $rules['discount.*']   = 'required|string';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'due_date.date_format' => 'Please select a date',
            'service_id.*.required' => 'The service name field is required.',
            'price.*.required' => 'The price field is required.',
            'price.*.numeric'  => 'The price must be a number.',
            'quantity.*.required' => 'The quantity field is required.',
            'quantity.*.integer'  => 'The quantity must be an integer.',
            'discount.*.required' => 'The discount field is required.',
        ];
    }

}
