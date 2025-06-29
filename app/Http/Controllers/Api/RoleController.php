<?php

namespace App\Http\Controllers\Api;

use App\Filters\RoleFilter;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleSimpleResource;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
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
    public function index(Request $request, RoleFilter $roleFilter)
    {
        try {
            $queryParameters = $roleFilter->transform($request);

            if($request->filled("pagination")) {
                $isPaginated = $request->input("pagination");
                $perPage = null;

                if($request->filled("per-page")) {
                    $perPage = $request->input("per-page");
                }

                if($isPaginated) {
                    return ApiResponse::success(
                        new RoleCollection(
                            $this->roleService->getAllPaginatedRoles($perPage, $queryParameters)
                            ->appends($request->query())
                        ),
                        "Successfully fetched all roles!",
                        200
                    );
                }
            }

           return ApiResponse::success([
                "roles" => RoleSimpleResource::collection($this->roleService->getAllRoles())
            ],
                "Successfully fetched all roles!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch roles. Please try again.",
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
            return ApiResponse::success([
                "role" => new RoleSimpleResource($this->roleService->createRole($request->validated()))
            ],
                "New role has been created successfully!",
                201
            );
        } catch (HttpException $e) {
           return APiResponse::error(
               "An error occurred while creating a new role!",
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
                "role" => new RoleSimpleResource($this->roleService->getRoleById($id))
            ],
                "Successfully fetched the role details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested role was not found!",
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
            return ApiResponse::success([
                "role" => new RoleSimpleResource($this->roleService->updateRole($id, $request->validated()))
            ],
                "The role was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the role!",
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
                "role" => new RoleSimpleResource($this->roleService->deleteRole($id))
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the role!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
