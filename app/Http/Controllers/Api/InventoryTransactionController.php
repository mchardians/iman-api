<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryTransactionRequest;
use App\Http\Requests\UpdateInventoryTransactionRequest;
use App\Repository\Services\InventoryTransactionService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InventoryTransactionController extends Controller
{
    private $inventoryTransactionService;

    public function __construct(InventoryTransactionService $inventoryTransactionService) {
        $this->inventoryTransactionService = $inventoryTransactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->inventoryTransactionService->getAll();
        } catch (HttpException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryTransactionRequest $request)
    {
        try {
            return $this->inventoryTransactionService->create($request->validated());
        } catch (HttpException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->inventoryTransactionService->getById($id);
        } catch (HttpException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryTransactionRequest $request, string $id)
    {
        try {
            return $this->inventoryTransactionService->update($request->validated(), $id);
        } catch (HttpException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return $this->inventoryTransactionService->delete($id);
        } catch (HttpException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
