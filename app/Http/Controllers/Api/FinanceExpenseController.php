<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceExpense\StoreFinanceExpenseRequest;
use App\Http\Requests\FinanceExpense\UpdateFinanceExpenseRequest;
use App\Services\FinanceExpenseService;
use App\Http\Resources\FinanceExpenseSimpleResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FinanceExpenseController extends Controller
{
    private $financeExpenseService;
    public function __construct(FinanceExpenseService $financeExpenseService) {
        $this->financeExpenseService = $financeExpenseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return ApiResponse::success([
                "finance_expenses" => FinanceExpenseSimpleResource::collection($this->financeExpenseService->getAllFinanceExpenses())
            ],
                "Successfully fetched all finance expenses!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch finance expenses. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinanceExpenseRequest $request)
    {
        try {
            return ApiResponse::success([
                "finance_expense" => new FinanceExpenseSimpleResource($this->financeExpenseService->createFinanceExpense($request->validated()))
            ],
                "New finance expense has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while creating a new finance expense!",
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
                "finance_expense" => new FinanceExpenseSimpleResource($this->financeExpenseService->getFinanceExpenseById($id))
            ],
                "Successfully fetched the finance expense details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested finance expense was not found!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinanceExpenseRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "finance_expense" => new FinanceExpenseSimpleResource($this->financeExpenseService->updateFinanceExpense($id, $request->validated()))
            ],
                "The finance expense was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the finance expense!",
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
                "finance_expense" => new FinanceExpenseSimpleResource($this->financeExpenseService->deleteFinanceExpense($id))
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the finance expense!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
