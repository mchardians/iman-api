<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFacilityReservationRequest;
use App\Http\Requests\UpdateFacilityReservationRequest;
use App\Repository\Services\FacilityReservationService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FacilityReservationController extends Controller
{
    private $facilityReservationService;

    public function __construct(FacilityReservationService $facilityReservationService)
    {
        $this->facilityReservationService = $facilityReservationService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->facilityReservationService->getAll();
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
    public function store(StoreFacilityReservationRequest $request)
    {
        try {
            return $this->facilityReservationService->create($request->validated());
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
            return $this->facilityReservationService->getById($id);
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
    public function update(UpdateFacilityReservationRequest $request, string $id)
    {
        try {
            return $this->facilityReservationService->update($request->validated(), $id);
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
            return $this->facilityReservationService->delete($id);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
