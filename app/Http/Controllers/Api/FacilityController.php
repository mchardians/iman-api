<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFacilityRequest;
use App\Http\Requests\Facility\StoreFacilityRequest;
use App\Http\Resources\FacilitySimpleResource;
use App\Services\FacilityService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FacilityController extends Controller
{
    private $facilityService;
    public function __construct(FacilityService $facilityService) {
        $this->facilityService = $facilityService;
    }

    public function index()
    {
        try {
            return ApiResponse::success([
                "facilities" => FacilitySimpleResource::collection($this->facilityService->getAllFacilities())
            ],
                "Successfully fetched all facilities!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch facilities. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    public function store(StoreFacilityRequest $request)
    {
        try {
            return ApiResponse::success([
                "facility" => new FacilitySimpleResource($this->facilityService->createFacility($request->validated()))
            ],
                "New facility has been created successfully!",
                201
            );
        } catch (HttpException $e) {
           return APiResponse::error(
               "An error occurred while creating a new facility!",
               $e->getMessage(),
               $e->getStatusCode()
           );
        }
    }

    public function show($id)
    {
        try {
            return ApiResponse::success([
                "facility" => new FacilitySimpleResource($this->facilityService->getFacilityById($id))
            ],
                "Successfully fetched the facility details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested facility was not found!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    public function update(UpdateFacilityRequest $request, $id)
    {
        try {
            return ApiResponse::success([
                "facility" => new FacilitySimpleResource($this->facilityService->updateFacility($id, $request->validated()))
            ],
                "The facility was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the facility!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    public function destroy($id)
    {
        try {
            return ApiResponse::success([
                "facility" => new FacilitySimpleResource($this->facilityService->deleteFacility($id))
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the facility!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}