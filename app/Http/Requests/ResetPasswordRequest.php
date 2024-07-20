<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
        $endpoint = basename(request()->url());
        $rule = [
            "password" => [
                'bail',
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed'
            ],
            'token' =>  'required|regex:/^[a-zA-Z0-9\s-]+$/|string'
        ];
        // Remove token from change password route
        if ($endpoint === "change-password") {
            $rule["old_password"] = "required|string";
            unset($rule['token']);
        }
        return $rule;
    }
}
