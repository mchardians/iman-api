<?php

namespace App\Http\Requests\FinanceCategory;

use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateFinanceCategoryRequest extends FormRequest
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
                        "name" => ["required", "string", Rule::unique("finance_categories", "name")->ignore(request()->route('category'))],
                        "type" => ["required", "in:income,expense"]
                     ],
            "PATCH" => [
                          "name" => ["sometimes", "required", "string", Rule::unique("finance_categories", "name")->ignore(request()->route('category'))],
                          "type" => ["sometimes", "required", "in:income,expense"]
                       ],
        };
    }

    protected function failedValidation(Validator $validator) {
        return ApiResponse::errorValidation($validator->errors());
    }
}
