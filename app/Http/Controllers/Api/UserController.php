<?php

namespace App\Http\Controllers\Api;

use App\Filters\UserFilter;
use App\Helpers\ApiResponse;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserSimpleResource;
use App\Services\UserService;
use Illuminate\Http\Request;
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
    public function index(Request $request, UserFilter $userFilter)
    {
        try {
            $queryParameters = $userFilter->transform($request);

            if($request->filled("pagination")) {
                $isPaginated = $request->input("pagination");
                $perPage = null;

                if($request->filled("per-page")) {
                    $perPage = $request->input("per-page");
                }

                if($isPaginated) {
                    return ApiResponse::success(
                        new UserCollection(
                            $this->userService->getAllPaginatedUsers($perPage, $queryParameters)
                            ->appends($request->query())
                        ),
                        "Successfully fetched all users!",
                        200
                    );
                }
            }

            return ApiResponse::success([
                "users" => UserSimpleResource::collection($this->userService->getAllUsers($queryParameters))
            ],
                "Successfully fetched all users!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch users. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
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
                "New user has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while creating a new user!",
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
                "Successfully fetched the user details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested user was not found!",
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
                "The user was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the user!",
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
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the user!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
