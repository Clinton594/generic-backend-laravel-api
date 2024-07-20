<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OTPRequest extends FormRequest
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
        $digits = stripos($this->url(), '/admin') ? 6 : 4;
        return [
            "otp" =>  "required|numeric|digits:{$digits}",
        ];
    }
}
