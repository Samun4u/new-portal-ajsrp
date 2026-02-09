<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class JournalRequest extends FormRequest
{

    public function rules()
    {

        $rules =  [
            'title' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            "website" => 'required|url',
            // "charge" => 'required|numeric|min:1',
            "journal_subject_id" => 'required|exists:journal_subjects,id',
            "status" => 'required|in:active,inactive',
            "impact_factor" => 'nullable|string|max:255',
            "editor_in_chief" => 'nullable|string|max:255',
            "chief_editor_name_ar" => 'nullable|string|max:255',
            "managing_editor_name_en" => 'nullable|string|max:255',
            "managing_editor_name_ar" => 'nullable|string|max:255',
            "signature_file" => 'nullable|image|max:2048',
            "managing_signature_file" => 'nullable|image|max:2048',
            "stamp_file" => 'nullable|image|max:2048',
        ];
        return $rules;

    }

    public function messages()
    {
        return [
            'journal_subject_id.required' => 'The journal subject field is required.',
            'journal_subject_id.exists'   => 'The selected journal subject does not exist.',
        ];
    }
}
