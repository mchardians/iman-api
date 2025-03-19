<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:facilities,name,' . $this->route('id'),
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable',
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
