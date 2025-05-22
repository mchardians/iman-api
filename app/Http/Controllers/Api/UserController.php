<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserSimpleResource;
use App\Services\UserService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::success(
            new UserCollection($this->userService->getAllUsers()),
            "Berhasil mendapatkan seluruh data user!"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            return ApiResponse::success([
                "user" => new UserSimpleResource($this->userService->createUser($request->validated()))
            ],
                "Berhasil menambahkan data user baru!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal menambahkan data user baru!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return ApiResponse::success([
                "user" => new UserSimpleResource($this->userService->getUserById($id))
            ],
                "User yang dicari ditemukan!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "User yang dicari tidak ditemukan!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "user" => new UserSimpleResource($this->userService->updateUser($id, $request->validated()))
            ],
                "Berhasil mengubah data user!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal mengubah data user!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return ApiResponse::success([
                "user" => new UserSimpleResource($this->userService->deleteUser($id))
            ],
                "Berhasil menghapus data user!",
                200
            );
        } catch (HttpException $e) {
            return response()->json([
                "status" => "error",
                "message" => "Gagal menghapus data user!",
                'errors' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
