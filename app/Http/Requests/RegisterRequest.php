<?php

namespace App\Http\Requests;

use App\Traits\AuthorizesAfterValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    use AuthorizesAfterValidation;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeValidated(): bool
    {
        // $this->getValidatorInstance()->validate();
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
        $regType = $this->request->get('type');
        $loginType = object(config('data.loginType'));
        $loginTypes = implode(',', array_values(config('data.loginType')));
        $countries = implode(',', array_keys(config('countries')));

        return [
            'email' =>  'bail|required|unique:users|max:240|email',
            "first_name" => 'bail|required|string',
            "last_name" => "bail|required|string",
            "phone" => 'bail|nullable|unique:users|digits:11',
            "password" => [
                'required',
                'string',
                $regType === $loginType->manual ? Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised() : '',
            ],
            "type" => "bail|required|regex:/^[a-zA-Z0-9\s-]+$/|in:{$loginTypes}",
            "country" => "bail|required|in:{$countries}",
        ];
    }
}
