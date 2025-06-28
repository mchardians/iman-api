<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class ApiResponse {
    public static function success($collection, ?string $message = null, int $statusCode = 200) {
        return response()->json([
            "status" => "success",
            "status_code" => $statusCode,
            "server_time" => now()->toDateTimeString(),
            "message" => $message,
            "data" => $collection,
        ], $statusCode);
    }

    public static function error(?string $message = null, ?string $errors = null, int $statusCode = 500) {
        return response()->json([
            "status" => "error",
            "status_code" => $statusCode,
            "server_time" => now()->toDateTimeString(),
            "message" => $message,
            "errors" => $errors
        ], $statusCode);
    }

    public static function errorValidation($errors) {
        throw new HttpResponseException(response()->json([
            "status" => "error",
            "status_code" => 422,
            "server_time" => now()->toDateTimeString(),
            "message" => "Invalid request! please review the submitted data.",
            "errors" => $errors
        ], 422));
    }
}