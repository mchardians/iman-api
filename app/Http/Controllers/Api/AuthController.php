<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Resources\TokenSimpleResource;
use App\Http\Resources\UserSimpleResource;
use App\Services\AuthService;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Get a JWT access token via given credentials",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/LoginRequest")
     *         ),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/LoginRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK. (Successfully authenticated to the system)",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/LoginResponse"),
     *             @OA\Examples(example="result", value={
     *                 "status": "success",
     *                 "status_code": 200,
     *                 "server_time": "2025-06-26 07:41:42",
     *                 "message": "Autentikasi berhasil! Selamat datang {{ user }}",
     *                 "data": {
     *                     "auth": {
     *                         "access_token": "blablabla...",
     *                         "token_type": "bearer",
     *                         "expires_in": 900,
     *                         "expires_in_human": "15mnt"
     *                     },
     *                     "user": {
     *                         "id": 1,
     *                         "code": "USR001",
     *                         "name": "John Doe",
     *                         "name_upper": "JOHN DOE",
     *                         "email": "john@example.com",
     *                         "photo": "https://example.com/images/john.jpg",
     *                         "role": {
     *                             "id": 1,
     *                             "role_code": "ROL/2506/0001",
     *                             "name": "jamaah-umum",
     *                             "created_at": "25 Juni 2025 14:30",
     *                             "created_at_human": "2 jam yang lalu"
     *                         },
     *                     "created_at": "25 Juni 2025 14:30",
     *                     "created_at_human": "2 jam yang lalu"
     *                     }
     *                 }
     *             }, summary="Simple Resource Response")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. (Authentication failed)",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={
     *                 "status": "error",
     *                 "status_code": 401,
     *                 "message": "The provided credentials do not match our records!",
     *                 "errors": "Unauthorized."
     *             }, summary="Simple Resource Response"),
     *         )
     *     )
     * )
     */
    public function login(AuthRequest $request)
    {
        try {
            return ApiResponse::success(
                new TokenSimpleResource($this->authService->authenticate($request->validated())),
                "Autentikasi berhasil! Selamat datang ". auth()->user()->name. ".",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The provided credentials do not match our records!",
                $e->getMessage(),
                401
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/me",
     *     tags={"Auth"},
     *     summary="Get current authenticated user",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user profile",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={
     *                 "status": "success",
     *                 "status_code": 200,
     *                 "server_time": "2025-06-26 07:41:42",
     *                 "message": "Berhasil mendapatkan informasi pengguna!",
     *                 "data": {
     *                     "user": {
     *                         "id": 1,
     *                         "code": "USR/2506/0001",
     *                         "name": "John Doe",
     *                         "name_upper": "JOHN DOE",
     *                         "email": "john@example.com",
     *                         "photo": "https://example.com/images/john.jpg",
     *                         "role": {
     *                             "id": 1,
     *                             "role_code": "ROL/2506/0001",
     *                             "name": "jamaah-umum",
     *                             "created_at": "25 Juni 2025 14:30",
     *                             "created_at_human": "2 jam yang lalu"
     *                         },
     *                         "created_at": "25 Juni 2025 14:30",
     *                         "created_at_human": "2 jam yang lalu"
     *                     }
     *                 }
     *             }, summary="Simple Resource Response")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. (Authentication failed)",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={
     *                 "status": "error",
     *                 "status_code": 401,
     *                 "message": "The provided credentials do not match our records!",
     *                 "errors": "Unauthorized."
     *             }, summary="Simple Resource Response"),
     *         )
     *     )
     * )
     */
    public function me()
    {
        try {
            return ApiResponse::success(
                [
                    "user" => new UserSimpleResource($this->authService->getAuthenticatedUser())
                ],
                "Berhasil mendapatkan informasi pengguna!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal mendapatkan informasi pengguna. Harap login!",
                $e->getMessage(),
                401
            );
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            return ApiResponse::success([
                "user" => new UserSimpleResource($this->authService->logout())
            ],
                "Pengguna berhasil keluar dari sistem!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal mendapatkan informasi pengguna. Harap login!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     tags={"Auth"},
     *     summary="Refresh an JWT access token",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="New token issued",
     *         @OA\JsonContent(ref="#/components/schemas/LoginResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function refresh()
    {
        try {
            return ApiResponse::success(
                new TokenSimpleResource($this->authService->getRefreshToken()),
                "Berhasil mendapatkan refresh token!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal mendapatkan refresh token!",
                $e->getMessage(),
                401
            );
        }
    }
}
