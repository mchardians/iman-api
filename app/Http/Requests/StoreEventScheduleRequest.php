<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreEventScheduleRequest extends FormRequest
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
            'event_id' => ["required", "exists:events,id"],
            'start_date' => ["required", "date"],
            'end_date' => ["required", "date"],
            'start_time' => ["required", "date_format:H:i"],
            'end_time' => ["required", "date_format:H:i"],
            'is_recurring' => ["required", "boolean"],
            'recurring_type' =>["required", "in:daily,weekly,monthly,yearly"],
            'recurring_day' =>["required", "string", "max:255"],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'errors'      => $validator->errors()
        ], 422));
    }
}
