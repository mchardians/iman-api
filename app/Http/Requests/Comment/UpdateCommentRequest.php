<?php

namespace App\Http\Requests\Comment;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UpdateCommentRequest extends FormRequest
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
            "content" => ["required", "string", "max:1000"],
            "user_id" => ["required", "exists:users,id"]
        ];
    }

    public function prepareForValidation() {
        try {
            $this->merge([
                "user_id" => Auth::user()->id
            ]);
        } catch (Exception $e) {
            throw new HttpException(422, $e->getMessage());
        }
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        return ApiResponse::errorValidation($validator->errors());
    }
}
