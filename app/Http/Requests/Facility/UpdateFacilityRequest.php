<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
    public function messages()
    {
        return [
            'name.required' => 'Nama fasilitas harus diisi.',
            'name.unique' => 'Nama fasilitas sudah digunakan.',
            'capacity.required' => 'Kapasitas harus diisi.',
            'capacity.integer' => 'Kapasitas harus berupa angka.',
            'status.required' => 'Status harus diisi.',
            'status.in' => 'Status harus "available" atau "unavailable".',
        ];
    }
}
