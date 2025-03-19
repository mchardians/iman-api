<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateInfaqTypeRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repository\Services\InfaqTypeService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Requests\StoreInfaqTypeRequest;

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
            return $this->infaqTypeService->getAll(); 
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
    public function store(StoreInfaqTypeRequest $request)
    {
        try {
            return $this->infaqTypeService->create($request->validated());
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
            return $this->infaqTypeService->getById($id);
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
    public function update(UpdateInfaqTypeRequest $request, string $id)
    {
        try {
            return $this->infaqTypeService->update($request->validated(), $id);
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
            return $this->infaqTypeService->delete($id);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
