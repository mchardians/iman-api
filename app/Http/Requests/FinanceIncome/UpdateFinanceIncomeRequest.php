<?php

namespace App\Http\Requests\FinanceIncome;

use Exception;
use Carbon\Carbon;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UpdateFinanceIncomeRequest extends FormRequest
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
                        "date" => ["required", "date_format:Y-m-d"],
                        "finance_category_id" => ["required", "exists:finance_categories,id"],
                        "description" => ["required", "string"],
                        "amount" => ["required", "numeric", "min:0"],
                        "transaction_receipt" => ["nullable", "file", "mimetypes:image/jpeg,image/png,application/pdf", "max:2048"]
                     ],
            "PATCH" => [
                          "date" => ["sometimes", "required", "date_format:Y-m-d"],
                          "finance_category_id" => ["sometimes", "required", "exists:finance_categories,id"],
                          "description" => ["sometimes", "required", "string"],
                          "amount" => ["sometimes", "required", "numeric", "min:0"],
                          "transaction_receipt" => ["sometimes", "nullable", "file", "mimetypes:image/jpeg,image/png,application/pdf", "max:2048"]
                       ],
        };
    }

    public function prepareForValidation() {
        if(!empty($this->date)) {
            try {
                $this->merge([
                    "date" => Carbon::createFromFormat("d-m-Y", trim($this->date))->format("Y-m-d")
                ]);
            } catch (Exception $e) {
                throw new HttpException(422, $e->getMessage());
            }
        }
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        return ApiResponse::errorValidation($validator->errors());
    }
}
