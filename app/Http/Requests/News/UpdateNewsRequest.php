<?php

namespace App\Http\Requests\News;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateNewsRequest extends FormRequest
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
                "title" => ["required", "string", "max:255"],
                "content" => ["required"],
                "category_id" => ["required", "array"],
                "category_id.*" => ["exists:news_categories,id"],
                "status" => ["required", "in:drafted,published,archived"],
                "thumbnail" => ["sometimes", "required", "image", "mimes:jpeg,png,jpg,gif", "max:2048"],
            ],
            "PATCH" => [
                "title" => ["sometimes", "required", "string", "max:255"],
                "content" => ["sometimes", "required"],
                "category_id" => ["sometimes", "required", "array"],
                "category_id.*" => ["sometimes", "exists:news_categories,id"],
                "status" => ["sometimes", "required", "in:drafted,published,archived"],
                "thumbnail" => ["sometimes", "required", "image", "mimes:jpeg,png,jpg,gif", "max:2048"],
            ],
        };
    }

    protected function failedValidation(Validator $validator)
    {
        return ApiResponse::errorValidation($validator->errors());
    }
}
