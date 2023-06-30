<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
            //
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'c_password'=>'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            "email.email"=>'Format email invalid',
            "email.unique"=>"Email already registered",
            "username.unique"=>"Username already registered",
            "username.required" => "username Can't Blank",
            "emai.required" => "username Can't Blank",
            "password.required" => "Password Can't Blank",
            "c_password.same"=>"Re-Password not same"
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        return $validator->errors();
    }
}   
