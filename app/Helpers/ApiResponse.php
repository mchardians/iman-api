<?php

namespace App\Helpers;

class ApiResponse {
    public static function success($collection, ?string $message = null, int $statusCode = 200) {
        return response()->json([
            "status" => "success",
            "message" => $message,
            "data" => $collection
        ], $statusCode);
    }

    public static function error(?string $message = null, ?string $errors = null, int $statusCode = 500) {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "errors" => $errors
        ], $statusCode);
    }
}