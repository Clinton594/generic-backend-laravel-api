<?php

namespace App\Http\Requests;

use App\Traits\AuthorizesAfterValidation;
use Illuminate\Foundation\Http\FormRequest;

class EmailOnlyRequest extends FormRequest
{
    use AuthorizesAfterValidation;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeValidated(): bool
    {
        $endpoint = basename(request()->url());


        // Allow these endpoints through or ProxyLogin is an admin
        return in_array(
            $endpoint,
            [
                "forgot-password",
                "resend-password-token",
                "validate-telegram-user"
            ]
        ) ? true : ($this->user())->type === config('data.userType.admin');
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
        ];
    }
}
