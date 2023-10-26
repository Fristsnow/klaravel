<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            "full_name" => 'required',
            "phone_number" => 'required',
            "shipping_address" => 'required',
            "card_number" => 'required',
            "name_on_card" => 'required',
            "exp_date" => 'required',
            "cvv" => 'required',
            "photo_id_list" => 'required',
        ];
    }
}
