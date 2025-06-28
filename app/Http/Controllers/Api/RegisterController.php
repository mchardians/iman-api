<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserSimpleResource;
use App\Helpers\ApiResponse;
use App\Services\RegisterService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Register\RegisterRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegisterController extends Controller
{
    private $registerService;

    public function __construct(RegisterService $registerService) {
        $this->registerService = $registerService;
    }

    public function __invoke(RegisterRequest $request)
    {
        try {
            return ApiResponse::success([
                "user" => new UserSimpleResource($this->registerService->register($request->validated()))
            ],
                "Account created successfully. You can now log in with your credentials!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Registration could not be completed at this time. Please try again later!",
                $e->getMessage(),
                500
            );
        }
    }
}
