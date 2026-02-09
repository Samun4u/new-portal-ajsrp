<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
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
            // 'name' => 'required|unique:packages,name,' . ignore($this->id)->whereNull('deleted_at'),
            "name" => ['required', Rule::unique('packages')->ignore($this->id)->whereNull('deleted_at')],
            'number_of_client' => 'required|numeric',
            'number_of_order' => 'required|numeric',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'required|numeric',
        ];
    }
}
