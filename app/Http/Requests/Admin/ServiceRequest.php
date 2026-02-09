<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{

    public function rules()
    {

    $userId = auth()->id();
        $rules =  [
            'service_name' => [
                   'bail',
                   'required',
                   'max:255',
                   Rule::unique('services', 'service_name')
                       ->where(function ($query) use ($userId) {
                           return $query->where('user_id', $userId)->whereNull('deleted_at');
                       })
                       ->ignore($this->id, 'id')
               ],
            "service_description" => 'bail|required',
            "payment_type" => 'bail|required',
            "faqs.question.*" => 'bail|required',
            "faqs.answer.*" => 'bail|required',
            "feature.name.*" => 'bail|required',
            "feature.value.*" => 'bail|required',
        ];

        if (($this->payment_type == PAYMENT_TYPE_ONETIME) && ($this->onetime_price == 0)) {
            $rules['onetime_price'] = 'bail|required';
        }

        if(($this->payment_type == PAYMENT_TYPE_RECURRING) && ($this->recurring_price == 0)){
            $rules['recurring_price'] = 'bail|required';
            $rules['recurring_type'] = 'bail|required';
        }

        return $rules;

    }

    public function messages()
    {
        return [
            "faqs.question.*.required" => __("This filed is required"),
            "faqs.answer.*.required" => __("This filed is required"),
            "feature.name.*.required" => __("This filed is required"),
            "feature.value.*.required" => __("This filed is required"),
        ];
    }
}
