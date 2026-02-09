<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewerApplicationRequest extends FormRequest
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
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:reviewer_applications,email|max:255',
            'institution' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'orcid' => 'nullable|string|max:255',
            'profile_links.google_scholar' => 'nullable|url',
            'profile_links.linkedin' => 'nullable|url',
            'profile_links.researchgate' => 'nullable|url',
            'profile_links.website' => 'nullable|url',
            'qualification' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:100',
            'subject_areas' => 'required|array|min:1',
            'subject_areas.*' => 'string|max:255',
            'keywords' => 'required|array|min:3|max:10',
            'keywords.*' => 'string|max:255',
            'review_experience' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'photo' => 'nullable|image|mimes:jpeg,png|max:5120',
            'agreement' => 'required|accepted',
            'acknowledgment' => 'required|in:yes,no'
        ];
    }

    public function messages()
    {
        return [
            'subject_areas.required' => 'Please select at least one subject area.',
            'subject_areas.min' => 'Please select at least one subject area.',
            'keywords.required' => 'Please enter between 3 and 10 keywords.',
            'keywords.min' => 'Please enter at least 3 keywords.',
            'keywords.max' => 'You can enter maximum 10 keywords.',
            'agreement.accepted' => 'You must agree to the reviewer terms.',
        ];
    }
}
