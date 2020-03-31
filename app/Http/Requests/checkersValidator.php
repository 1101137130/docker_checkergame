<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class checkersValidator extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'itemname' => 'required|string|max:15',
            'rate' => 'required|integer',
        ];
    }
    public function messages()
    {
        return [
            "itemname.required" => "品項名稱不該超過15個字元",
            "rate.required" => '必須是數字',
        ];
    }
}
