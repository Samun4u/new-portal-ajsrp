<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{

    public function rules()
    {
        $rules =  [
            "order_id" => 'bail|required',
            "description" => 'bail|required',
        ];

        return $rules;

    }
}
