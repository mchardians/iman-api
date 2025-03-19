<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeInfaqTransactionRequest;
use App\Http\Requests\UpdateIncomeInfaqTransactionRequest;
use App\Repository\Services\IncomeInfaqTransactionService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IncomeInfaqTransactionController extends Controller
{
    private $incomeInfaqTransactionService;
    
    public function __construct(IncomeInfaqTransactionService $incomeInfaqTransactionService) {
        $this->incomeInfaqTransactionService = $incomeInfaqTransactionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->incomeInfaqTransactionService->getAll();
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
    public function store(StoreIncomeInfaqTransactionRequest $request)
    {
        try {
            return $this->incomeInfaqTransactionService->create($request->validated());
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
            return $this->incomeInfaqTransactionService->getById($id);
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
    public function update(UpdateIncomeInfaqTransactionRequest $request, string $id)
    {
        try {
            return $this->incomeInfaqTransactionService->update($request->validated(), $id);
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
            return $this->incomeInfaqTransactionService->delete($id);
        } catch (HttpException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
