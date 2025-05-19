<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
           return ApiResponse::success(
               $this->roleService->getAllRoles(),
           "Berhasil mendapatkan data role");
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal mendapatkan data role",
                $e->getMessage(),
                $e->getStatusCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            return ApiResponse::success(
                $this->roleService->createRole($request->validated()),
                "Berhasil menambahkan data role baru!",
                201
            );
        } catch (HttpException $e) {
           return APiResponse::error(
               "Gagal menambahkan data role baru!",
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
            return ApiResponse::success(
                $this->roleService->getRoleById($id),
                "Role yang dicari ditemukan!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Role yang dicari tidak ditemukan!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        try {
            return ApiResponse::success(
                $this->roleService->updateRole($id, $request->validated()),
                "Berhasil mengubah data role!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal mengubah data role!",
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
            return ApiResponse::success(
                $this->roleService->deleteRole($id),
                "Berhasil menghapus data role!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal menghapus data role!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
