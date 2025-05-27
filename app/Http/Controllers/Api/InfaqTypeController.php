<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Services\InfaqTypeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\InfaqTypeCollection;
use App\Http\Requests\InfaqType\StoreInfaqTypeRequest;
use App\Http\Requests\InfaqType\UpdateInfaqTypeRequest;
use App\Http\Resources\InfaqTypeSimpleResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InfaqTypeController extends Controller
{
    private $infaqTypeService;

    public function __construct(InfaqTypeService $infaqTypeService)
    {
        $this->infaqTypeService = $infaqTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return ApiResponse::success([
                "infaq_types" => InfaqTypeSimpleResource::collection($this->infaqTypeService->getAllInfaqTypes())
            ],
                "Successfully fetched all infaq types!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch infaq types. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInfaqTypeRequest $request)
    {
        try {
            return ApiResponse::success([
                "infaq_type" => new InfaqTypeSimpleResource($this->infaqTypeService->createInfaqType($request->validated()))
            ],
                "New infaq type has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while creating a new infaq type!",
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
                "infaq_type" => new InfaqTypeSimpleResource($this->infaqTypeService->getInfaqTypeById($id))
            ],
                "Successfully fetched the infaq type details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested infaq type was not found!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInfaqTypeRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "infaq_type" => new InfaqTypeSimpleResource($this->infaqTypeService->updateInfaqType($id, $request->validated()))
            ],
                "The infaq type was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the infaq type!",
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
                "infaq_type" => new InfaqTypeSimpleResource($this->infaqTypeService->deleteInfaqType($id))
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
