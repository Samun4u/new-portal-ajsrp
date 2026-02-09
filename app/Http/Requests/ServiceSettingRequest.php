<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceSettingRequest extends FormRequest
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
            'name' => 'required',
            'sub_title' => 'required',
            'title' => 'required',
            'description' => 'required',
        ];

        if (!$this->id) {
            $rules['image'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'sub_title.required' => 'sub title is required!',
        ];
    }
}
