<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateFacilityRequest extends FormRequest
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
            "name" => ["required", "string",  Rule::unique("facilities", "name")->ignore(request()->route("facility"))],
            "description" => ["required"],
            "capacity" => ["required", "numeric", "min:0"],
            "price_per_hour" => ["required", "numeric", "min:0"],
            "status" => ["required", "in:available,maintenance,unavailable"],
            "cover_image" => ["nullable", "image", "mimes:jpg,png,jpeg,webp", "max:2048"],
            "facility_previews.*" => ["nullable", "image", "mimes:jpg,png,jpeg,webp", "max:2048"],
            "remove_facility_preview_id" => ["nullable", "array"],
            "remove_facility_preview_id.*" => ["exists:facility_previews,id"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return ApiResponse::errorValidation($validator->errors());
    }
}
