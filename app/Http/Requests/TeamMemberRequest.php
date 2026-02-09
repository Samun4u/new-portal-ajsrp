<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamMemberRequest extends FormRequest
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
        $id = isset($this->id) ? decrypt($this->id) : null;
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'designation_id' => 'required',
            'role' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg'
        ];
    }
}
