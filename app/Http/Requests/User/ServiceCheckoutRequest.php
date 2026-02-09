<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ServiceCheckoutRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'gateway' => 'required|string',
            'gateway_id' => 'required|string',
            'currency' => 'required|string',
            'item_id' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'gateway' => 'The name gateway is required',
            'currency' => 'The name gateway is required',
        ];
    }
}
