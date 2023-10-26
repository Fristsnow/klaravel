<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'email' => 'required|email:filter|unique:users,email',
            'full_name' => 'required',
            'password' => 'required',
            'repeat_password' => 'required|same:password'
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => json_encode([
                "message" => "email has already be used",
                "code" => 409
            ]),
            'repeat_password.same' => json_encode([
                'message' => 'repeat_password not same with password field',
                'code' => 422
            ]),
            'full_name' => json_encode([
                "message" => "email has already be used",
                "code" => 422
            ])
        ];
//        return parent::messages(); // TODO: Change the autogenerated stub
    }
}