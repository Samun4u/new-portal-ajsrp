<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutOrderPlaceRequest extends FormRequest
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
            'currency' => 'required|string',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'checkout_details' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name' => 'The name field is required',
            'email' => 'The email field is required',
            'checkout_details' => __(SOMETHING_WENT_WRONG),
        ];
    }
}
