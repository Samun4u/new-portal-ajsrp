<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
            'to' => 'required|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'template_id' => 'nullable|exists:bulk_email_templates,id'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $emails = explode(',', $this->to);
            foreach ($emails as $email) {
                if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                    $validator->errors()->add('to', 'Invalid email address: ' . $email);
                }
            }

            if ($this->bcc) {
                $bccEmails = explode(',', $this->bcc);
                foreach ($bccEmails as $email) {
                    if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                        $validator->errors()->add('bcc', 'Invalid BCC email address: ' . $email);
                    }
                }
            }
        });
    }
}
