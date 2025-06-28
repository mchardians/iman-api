<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e) {
        if ($e instanceof TokenExpiredException) {
            return ApiResponse::error("Access token has expired!", $e->getMessage(), 401);
        }

        if ($e instanceof TokenInvalidException) {
            return ApiResponse::error("Invalid token provided!", $e->getMessage(), 401);
        }

        if ($e instanceof JWTException) {
            return ApiResponse::error("Access token not found, request aborted!", $e->getMessage(), 401);
        }

        return parent::render($request, $e);
    }

    public function unauthenticated($request, AuthenticationException $exception) {
        return ApiResponse::error("Authentication is required to access this resource!", $exception->getMessage(), 401);
    }
}
