<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionReviewerRequest extends FormRequest
{

    public function rules()
    {
        $rules =  [
            "order_id" => 'bail|required',
            "reviewer_id" => 'bail|required',
            "description" => 'bail|required',
            'file' => 'nullable|array',
            'file.*' => 'file|mimes:pdf,docx,png|max:10240', // 10MB per file
        ];

        return $rules;

    }
}
