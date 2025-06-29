<?php

namespace App\Http\Requests\User;

use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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

        return match ($method) {
            "PUT" => [
                        "name" => ["required", "string", "max:255"],
                        "email" => [
                            "required", "email",
                            Rule::unique("users", "email")->ignore(request()->route('user'))
                        ],
                        "password" => ["sometimes", "string", "min:8"],
                        "photo" => ["nullable", "image", "mimes:jpg,png,jpeg,webp"],
                        "role_id" => ["required", "exists:roles,id"]
                    ],
            "PATCH" => [
                          "name" => ["sometimes", "required", "string", "max:255"],
                          "email" => [
                              "sometimes", "required", "email",
                              Rule::unique("users", "email")->ignore(request()->route('user'))
                          ],
                          "password" => ["sometimes", "string", "min:8"],
                          "photo" => ["sometimes", "nullable", "image", "mimes:jpg,png,jpeg,webp"],
                          "role_id" => ["sometimes", "required", "exists:roles,id"]
                       ],
        };
    }

    protected function failedValidation(Validator $validator)
    {
        return ApiResponse::errorValidation($validator->errors());
    }
}
