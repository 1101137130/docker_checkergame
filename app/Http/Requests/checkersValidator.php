<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class checkersValidator extends FormRequest
{
    private static $_instance  = null ;
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
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
        return [];
    }
    
    public function messages()
    {
        return [
            'itemname.max' =>'The :attribute field should be shorter than 15 characters.',
            'integer' => 'The :attribute field should be Integer',
            'required' => 'The :attribute field is required.',
            'username.max' => 'The :attribute field should be shorter than 20 characters.',
            'unique' => 'The :attribute field is repeated.',
            'password.min' => 'The :attribute field should be bigger than 6 characters.',
            'confirmed' => 'The :attribute field should be the same.',
            'email' => 'The :attribute field should be like emample@examel33.com',
        ];
    }
}
