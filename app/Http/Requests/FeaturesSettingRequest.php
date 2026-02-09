<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeaturesSettingRequest extends FormRequest
{

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
            "title" => ['required', Rule::unique('features_settings')->ignore($this->id)->whereNull('deleted_at')],
        ];

        if($this->id){
            $rules['image'] = 'nullable|mimes:jpg,jpeg,png';
        }
        else{
            $rules['image'] = 'required|mimes:jpg,jpeg,png';
        }

        return $rules;
    }
}
