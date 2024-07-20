<?php

namespace App\Http\Requests;

use App\Traits\AuthorizesAfterValidation;
use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
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
        $tokenFor = array_values(config("data.tokenFor"));

        return in_array($this->request->get('tokenFor'), $tokenFor);
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
            "tokenFor" => 'bail|required|string',
        ];
    }
}
