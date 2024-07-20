<?php

namespace App\Http\Requests;

use App\Traits\AuthorizesAfterValidation;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use AuthorizesAfterValidation;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeValidated(): bool
    {
        $loginTypes = array_values(config("data.loginType"));

        return in_array($this->request->get('type'), $loginTypes);
    }

    public function statusCode(): int
    {
        return 400;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' =>  'bail|required|email',
            "password" => 'bail|required',
            "type" => 'required|string|regex:/^[a-zA-Z0-9\s-]+$/'
        ];
    }
}
