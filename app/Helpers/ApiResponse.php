<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class ApiResponse {
    /**
     * @OA\Schema(
     *     schema="SuccessResponse",
     *     type="object",
     *     required={"status", "status_code", "server_time", "message", "data"},
     *     @OA\Property(property="status", type="string"),
     *     @OA\Property(property="status_code", type="integer"),
     *     @OA\Property(property="server_time", type="string"),
     *     @OA\Property(property="message", type="string"),
     *     @OA\Property(property="data", type="object"),
     *     example={
     *         "status": "success",
     *         "status_code": 200,
     *         "server_time": "2025-06-25 22:20:47",
     *         "message": "This is success message",
     *         "data": {
     *             "key": "value..."
     *         }
     *     }
     * )
     */
    public static function success($collection, ?string $message = null, int $statusCode = 200) {
        return response()->json([
            "status" => "success",
            "status_code" => $statusCode,
            "server_time" => now()->toDateTimeString(),
            "message" => $message,
            "data" => $collection,
        ], $statusCode);
    }

    /**
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     type="object",
     *     required={"status", "status_code", "message", "errors"},
     *     @OA\Property(property="status", type="string"),
     *     @OA\Property(property="status_code", type="integer"),
     *     @OA\Property(property="message", type="string"),
     *     @OA\Property(property="errors", type="string"),
     *     example={
     *         "status": "error",
     *         "status_code": 500,
     *         "message": "An unexpected error occured",
     *         "errors": "Internal server error"
     *     }
     * )
     */
    public static function error(?string $message = null, ?string $errors = null, int $statusCode = 500) {
        return response()->json([
            "status" => "error",
            "status_code" => $statusCode,
            "message" => $message,
            "errors" => $errors
        ], $statusCode);
    }

    /**
     * @OA\Schema(
     *     schema="ErrorValidationResponse",
     *     type="object",
     *     required={"status", "status_code", "message", "errors"},
     *     @OA\Property(property="status", type="string"),
     *     @OA\Property(property="status_code", type="integer"),
     *     @OA\Property(property="message", type="string"),
     *     @OA\Property(property="errors", type="object"),
     *     example={
     *         "status": "error",
     *         "status_code": 422,
     *         "message": "Invalid request! please review the submitted data",
     *         "errors": {
     *             "input": {"error message"}
     *         }
     *     }
     * )
     */
    public static function errorValidation($errors) {
        throw new HttpResponseException(response()->json([
            "status" => "error",
            "status_code" => 422,
            "message" => "Invalid request! please review the submitted data.",
            "errors" => $errors
        ], 422));
    }
}