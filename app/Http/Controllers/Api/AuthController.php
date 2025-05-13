<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Services\AuthService;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        try {
            return ApiResponse::success(
                $this->authService->authenticate($request->validated()),
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
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            return ApiResponse::success(
                $this->authService->getAuthenticatedUser(),
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
            return ApiResponse::success(
                $this->authService->logout(),
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
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return ApiResponse::success(
                $this->authService->getRefreshToken(),
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
