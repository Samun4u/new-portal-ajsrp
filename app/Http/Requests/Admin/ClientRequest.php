<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{

    public function rules()
    {
        $rules =  [
            "client_name" => 'bail|required|max:255|unique:services,service_name,'. $this->id.',id,deleted_at,NULL',
            "client_email" => 'bail|required',
        ];
        if(!$this->id) {
            return array_merge($rules, ['client_password' => 'required|min:6']);
        }
        return $rules;

    }
}
