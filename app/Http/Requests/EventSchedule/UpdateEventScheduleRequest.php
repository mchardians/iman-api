<?php

namespace App\Http\Requests\EventSchedule;

use Exception;
use Carbon\Carbon;
use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UpdateEventScheduleRequest extends FormRequest
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
                "title" => ["required", Rule::unique("event_schedules", "title")->ignore(request()->route('user')),
                    "string", "max:255"
                ],
                "description" => ["nullable", "string"],
                "event_date" => ["required", "date"],
                "start_time" => ["required", "date_format:H:i"],
                "end_time" => ["required", "date_format:H:i", "after:start_time"],
                "location" => ["required", "string", "max:255"],
                "speaker" => ["nullable", "string", "max:255"],
                "banner" => ["nullable", "image", "mimes:jpeg,png,jpg", "max:2048"],
                "status" => ["required", "in:draft,scheduled,finished,cancelled,archived"],
                "facility_id" => ["nullable", "exists:facilities,id"],
            ],
            "PATCH" => [
                "title" => ["sometimes", "required", "string", "max:255"],
                "description" => ["sometimes", "nullable", "string"],
                "event_date" => ["sometimes", "required", "date", "date_format:d-m-Y"],
                "start_time" => ["sometimes", "required", "date_format:H:i"],
                "end_time" => ["sometimes", "required", "date_format:H:i", "after:start_time"],
                "location" => ["sometimes", "required", "string", "max:255"],
                "speaker" => ["sometimes", "nullable", "string", "max:255"],
                "banner" => ["sometimes", "image", "mimes:jpeg,png,jpg", "max:2048"],
                "status" => ["sometimes", "required", "in:drafted,scheduled,finished,cancelled,archived"],
                "facility_id" => ["sometimes", "nullable", "exists:facilities,id"],
            ],
        };
    }

    protected function prepareForValidation() {
        if(!empty($this->event_date)) {
            try {
                $this->merge([
                    "event_date" => Carbon::createFromFormat("d-m-Y", trim($this->event_date))
                    ->format("Y-m-d")
                ]);
            } catch (Exception $e) {
                throw new HttpException(422, $e->getMessage());
            }
        }
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        return ApiResponse::errorValidation($validator->errors());
    }
}
