<?php

namespace App\Http\Requests;

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
        return [
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required", "email",
                Rule::unique("users", "email")->ignore(request()->route('user'))
            ],
            "password" => ["required", "string", "min:8"],
            "photo" => ["nullable", "image", "mimes:jpg,png,jpeg,webp"],
            "role_id" => ["required", "exists:roles,id"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "status" => "error",
            "message" => "Invalid request! please review the submitted data.",
            "errors" => $validator->errors()
        ], 422));
    }
}
