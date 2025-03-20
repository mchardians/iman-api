<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventScheduleRequest;
use App\Http\Requests\UpdateEventScheduleRequest;
use App\Repository\Services\EventScheduleService;
use Symfony\Component\HttpKernel\Exception\HttpException;


class EventScheduleController extends Controller
{
    private $eventScheduleService;

    public function __construct(EventScheduleService $eventScheduleService)
    {
        $this->eventScheduleService = $eventScheduleService;
    }

    public function index()
    {
        try {
            return $this->eventScheduleService->getAll();
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventScheduleRequest $request)
    {
        try {
            // dd($request->validated());
            return $this->eventScheduleService->create($request->validated());
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->eventScheduleService->getById($id);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventScheduleRequest $request, string $id)
    {
        try {
            return $this->eventScheduleService->update($request->validated(), $id);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return $this->eventScheduleService->delete($id);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
