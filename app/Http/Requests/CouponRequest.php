<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        if (isset($this->id)) {
            $rules = [
                "title" => 'bail|required|string|max:255|unique:coupons,title,' . decrypt($this->id) . ',id,deleted_at,NULL',
                "code" => 'bail|required|unique:coupons,code,' . decrypt($this->id) . ',id,deleted_at,NULL',
                "discount_amount" => 'required|numeric',
                "valid_date" => 'required|date',
                "service_ids" => 'required',
            ];
        } else {
            $rules = [
                "title" => 'bail|required|string|max:255|unique:coupons,title,NULL,id,deleted_at,NULL',
                "code" => 'bail|required|unique:coupons,code,NULL,id,deleted_at,NULL',
                "discount_amount" => 'required|numeric',
                "valid_date" => 'required|date',
                "service_ids" => 'required',
            ];
        }
        return $rules;
    }
}
