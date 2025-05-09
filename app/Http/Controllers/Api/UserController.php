<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
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
        return response()->json([
            "status" => "success",
            "message" => "Berhasil mendapatkan seluruh data user!",
            "data" => $this->userService->getAllUsers()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            return response()->json([
                "status" => "success",
                "message" => "Berhasil menambahkan data user baru!",
                "data" => $this->userService->createUser($request->validated())
            ]);
        } catch (HttpException $e) {
            return response()->json([
                "status" => "error",
                "message" => "Gagal menambahkan data user baru.",
                'errors' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->userService->getUserById($id);
        } catch (HttpException $e) {
            return response()->json([
                "status" => "error",
                "message" => "User yang dicari tidak ditemukan!",
                'errors' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            return response()->json([
                "status" => "success",
                "message" => "Berhasil mengubah data user!",
                "data" => $this->userService->updateUser($id, $request->validated())
            ]);
        } catch (HttpException $e) {
            return response()->json([
                "status" => "error",
                "message" => "Gagal mengubah data user!",
                'errors' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return response()->json([
                "status" => "success",
                "message" => "Berhasil menghapus data user!",
                "data" => $this->userService->deleteUser($id)
            ]);
        } catch (HttpException $e) {
            return response()->json([
                "status" => "error",
                "message" => "Gagal menghapus data user!",
                'errors' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
