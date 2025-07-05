<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Filters\EventScheduleFilter;
use App\Http\Controllers\Controller;
use App\Services\EventScheduleService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Requests\EventSchedule\StoreEventScheduleRequest;
use App\Http\Requests\EventSchedule\UpdateEventScheduleRequest;
use App\Http\Resources\EventScheduleCollection;
use App\Http\Resources\EventScheduleSimpleResource;

class EventScheduleController extends Controller
{
    private $eventScheduleService;

    public function __construct(EventScheduleService $eventScheduleService)
    {
        $this->eventScheduleService = $eventScheduleService;
    }

    public function index(Request $request, EventScheduleFilter $eventScheduleFilter)
    {
        try {
            $queryParameters = $eventScheduleFilter->transform($request);

            if($request->filled("pagination")) {
                $isPaginated = $request->input("pagination");
                $pageSize = null;

                if($request->filled("page_size")) {
                    $pageSize = $request->input("page_size");
                }

                if($isPaginated) {
                    return ApiResponse::success(
                        new EventScheduleCollection(
                            $this->eventScheduleService->getAllPaginatedEventSchedules($pageSize, $queryParameters)
                            ->appends($request->query())
                        ),
                        "Successfully fetched all event schedules!",
                        200
                    );
                }
            }

            return ApiResponse::success([
                "event_schedules" => EventScheduleSimpleResource::collection(
                    $this->eventScheduleService->getAllEventSchedules($queryParameters)
                )
            ],
                "Successfully fetched all event schedules!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch event schedules. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventScheduleRequest $request)
    {
        try {
            return ApiResponse::success([
                "event_schedule" => new EventScheduleSimpleResource(
                    $this->eventScheduleService->createEventSchedule($request->validated())
                )
            ],
                "New event schedule has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return APiResponse::error(
               "An error occurred while creating a new event schedule!",
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
                "event_schedule" => new EventScheduleSimpleResource(
                    $this->eventScheduleService->getEventScheduleById($id)
                )
            ],
                "Successfully fetched the event schedule details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested event schedule was not found!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventScheduleRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "event_schedule" => new EventScheduleSimpleResource(
                    $this->eventScheduleService->updateEventSchedule($id, $request->validated())
                )
            ],
                "The event schedule was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the event schedule!",
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
                "event_schedule" => new EventScheduleSimpleResource(
                    $this->eventScheduleService->deleteEventSchedule($id)
                )
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the event schedule!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
