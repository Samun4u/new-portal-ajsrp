<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $rules = [
            "name" => ['required', 'string', 'max:255'],
            "mobile" => 'bail|required|min:6|unique:users,mobile,' . auth()->id(),
            "country" =>  'bail|required|max:195',
            "city" =>  'bail|required|max:195',
            "zip_code" =>  'bail|required|max:195',
            "address" =>  'bail|required|max:195',
            "currency" =>  'bail|required|max:195',
            "company_name" =>  'bail|nullable',
            "company_designation" =>  'bail|nullable',
            "company_country" =>  'bail|nullable',
            "company_city" =>  'bail|nullable',
            "company_zip_code" =>  'bail|nullable',
            "company_address" =>  'bail|nullable',
            'image' => 'bail|nullable|mimes:jpg,jpeg,png',
            'company_logo' => 'bail|nullable|mimes:jpg,jpeg,png',
        ];
        return $rules;
    }
}
