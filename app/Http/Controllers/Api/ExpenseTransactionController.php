<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseTransactionRequest;
use App\Http\Requests\UpdateExpenseTransactionRequest;
use App\Repository\Services\ExpenseTransactionService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExpenseTransactionController extends Controller
{
    private $expenseTransactionService;

    public function __construct(ExpenseTransactionService $expenseTransactionService) {
        $this->expenseTransactionService = $expenseTransactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->expenseTransactionService->getAll();
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
    public function store(StoreExpenseTransactionRequest $request)
    {
        try {
            return $this->expenseTransactionService->create($request->validated());
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
            return $this->expenseTransactionService->getById($id);
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
    public function update(UpdateExpenseTransactionRequest $request, string $id)
    {
        try {
            return $this->expenseTransactionService->update($request->validated(), $id);
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
            return $this->expenseTransactionService->delete($id);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
