<?php

namespace App\Http\Requests\ActivitySchedule;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreActivityScheduleRequest extends FormRequest
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
            "title" => ["required", "string", "max:255"],
            "description" => ["required", "string"],
            "day_of_week" => ["required", "string", "in:senin,selasa,rabu,kamis,jumat,sabtu,minggu"],
            "start_time" => ["required", "date_format:H:i"],
            "end_time" => ["required", "date_format:H:i"],
            "repeat_type" => ["required", "in:daily,weekly,monthly"],
            "status" => ["required", "in:active,inactive,cancelled,done"],
            "location" => ["required", "string", "max:255"],
        ];
    }

    protected function prepareForValidation() {
        if(!empty($this->day_of_week)) {
            try {
                $this->merge([
                    "day_of_week" => strtolower($this->day_of_week),
                    "status" => "inactive"
                ]);
            } catch (Exception $e) {
                throw new HttpException(422, $e->getMessage());
            }
        }
    }

    protected function failedValidation(Validator $validator)
    {
        return ApiResponse::errorValidation($validator->errors());
    }
}
