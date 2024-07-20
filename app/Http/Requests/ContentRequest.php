<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;


class ContentRequest extends FormRequest
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
        $method = $this->method();
        $contentType = implode(",", array_values(config("data.contentType")));
        $approval = implode(",", array_values(config("data.approval")));
        $required = $this->method() !== "POST" ? "nullable" : "required";

        switch ($method) {
            case 'GET':
                return [
                    "type" => "bail|string|required|in:{$contentType}"
                ];
                break;
            case 'POST':
                POST:

                return [
                    "type" => "bail|string|{$required}|in:{$contentType}",
                    "title" => "bail|string|required",
                    "body" => "bail|string|required|min:10",
                    "url" => "bail|string|nullable",
                    "image" => "bail|string|nullable|min:30",
                    "status" => "bail|string|nullable|in:{$approval}",
                ];
                break;
            case 'PATCH':
                goto POST;
                break;

            default:
                return [
                    //
                ];
                break;
        }
    }

    public function messages()
    {
        return [];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->sometimes('image', 'required', function (Fluent $input) {
            $method = $this->method();
            return in_array($input->type, ['PODCAST', 'TESTIMONIAL', 'HOW_TO']) && $method !== 'GET';
        });
    }
}
