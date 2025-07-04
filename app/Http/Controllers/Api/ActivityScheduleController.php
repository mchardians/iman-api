<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Filters\ActivityScheduleFilter;
use App\Services\ActivityScheduleService;
use App\Http\Resources\ActivityScheduleCollection;
use App\Http\Resources\ActivityScheduleSimpleResource;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Requests\ActivitySchedule\StoreActivityScheduleRequest;
use App\Http\Requests\ActivitySchedule\UpdateActivityScheduleRequest;

class ActivityScheduleController extends Controller
{
    private $activityScheduleService;
    public function __construct(ActivityScheduleService $activityScheduleService) {
        $this->activityScheduleService = $activityScheduleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ActivityScheduleFilter $activityScheduleFilter)
    {
        try {
            $queryParameters = $activityScheduleFilter->transform($request);

            if($request->filled("pagination")) {
                $isPaginated = $request->input("pagination");
                $pageSize = null;

                if($request->filled("page_size")) {
                    $pageSize = $request->input("page_size");
                }

                if($isPaginated) {
                    return ApiResponse::success(
                        new ActivityScheduleCollection(
                            $this->activityScheduleService
                            ->getAllPaginatedActivitySchedules($pageSize, $queryParameters)
                            ->appends($request->query())
                        ),
                        "Successfully fetched all activity schedules!",
                        200
                    );
                }
            }

            return ApiResponse::success([
                "activity_schedules" => ActivityScheduleSimpleResource::collection(
                    $this->activityScheduleService->getAllActivitySchedules($queryParameters)
                )
            ],
                "Successfully fetched all activity schedules!"
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch activity schedules. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityScheduleRequest $request)
    {
        try {
            return ApiResponse::success([
                "activity_schedule" => new ActivityScheduleSimpleResource(
                    $this->activityScheduleService->createActivitySchedule(
                        $request->validated()
                    )
                )
            ],
                "New activity schedule has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch activity schedules. Please try again.",
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
                "activity_schedule" => $this->activityScheduleService->getActivityScheduleById($id)
            ],
                "Successfully fetched the activity schedule details!"
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested activity schedule was not found",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityScheduleRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "activity_schedule" => new ActivityScheduleSimpleResource($this->activityScheduleService
                ->updateActivitySchedule($id, $request->validated()))
            ],
                "The activity schedule was updated successfully!"
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occured while updating the activity schedule!",
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
                "activity_schedule" => new ActivityScheduleSimpleResource(
                    $this->activityScheduleService->deleteActivitySchedule($id)
                )
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occured while deleting the activity schedule!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    public function setStatus(UpdateActivityScheduleRequest $request, string $id) {
        try {
            return ApiResponse::success([
                "activity_schedule" => new ActivityScheduleSimpleResource($this->activityScheduleService
                ->setActivityScheduleStatus($id, $request->validated())
                )
            ],
                "The activity schedule was updated successfully!"
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occured while updating the activity schedule status!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}