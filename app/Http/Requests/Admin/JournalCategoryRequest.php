<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class JournalCategoryRequest extends FormRequest
{

    public function rules()
    {

        $rules =  [
            'name' => 'required',
            'name_ar' => 'required',
            "status" => 'required',
        ];
        return $rules;

    }
}
