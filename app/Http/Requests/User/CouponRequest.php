<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        if(isset($this->id)){
            $rules = [
                "coupon_name" => 'bail|required|string|max:255|unique:coupons,name,'. decrypt($this->id).',id,deleted_at,NULL',
                "coupon_code" => 'bail|required|unique:coupons,code,'. decrypt($this->id).',id,deleted_at,NULL',
                "discount_type" => 'bail|required',
                "discount" => 'bail|required',
                "product_plan" => 'bail|required',
                "valid_date" => 'bail|required',
            ];
        }else{
            $rules = [
                "coupon_name" => 'bail|required|string|max:255|unique:coupons,name,NULL,id,deleted_at,NULL',
                "coupon_code" => 'bail|required|unique:coupons,code,NULL,id,deleted_at,NULL',
                "discount_type" => 'bail|required',
                "discount" => 'bail|required',
                "product_plan" => 'bail|required',
                "valid_date" => 'bail|required',
            ];
        }
        if($this->redemption_type == REDEMPTION_TYPE_LIMITED_NUMBER){
            $rules = [
                "maximum_redemption" => 'bail|required',
            ];
        }
        return $rules;
    }
}
