<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreEditorialBoardApplicationRequest extends FormRequest
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

    public function rules()
    {
        return [
            'full_name' => 'required|string|min:2',
            'email' => 'required|email',
            'phone' => 'nullable',
            'country' => 'required',
            'linkedin' => 'nullable|url',
            'degree' => 'required|string',
            'specialization' => 'required|string',
            'title' => 'required|string',
            'institution' => 'required|string',
            'experience' => 'required|integer|min:0|max:50',
            'publications' => 'nullable|string',
            'supporting_doc' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'editorial_board_exp' => 'required|in:0,1',
            'editorial_details' => 'nullable|required_if:editorial_board_exp,1|string',
            'peer_reviewer_exp' => 'required|in:0,1',
            'reviewer_details' => 'nullable|required_if:peer_reviewer_exp,1|string',
            'interests' => 'required|array|min:1',
            'interests.*' => 'string',
            'other_interest' => 'nullable|required_if:interests,Other|string',
            'purpose' => 'required|string|max:1000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'commitment' => 'required|accepted',
            'acknowledgment' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'editorial_details.required_if' => 'Please specify your editorial board experience.',
            'reviewer_details.required_if' => 'Please specify your peer reviewing experience.',
            'other_interest.required_if' => 'Please specify your area of interest.',
            'interests.required' => 'Please select at least one area of interest.',
            'cv.required' => 'Please upload your CV.',
            'commitment.accepted' => 'You must agree to the commitment statement.'
        ];
    }
}
