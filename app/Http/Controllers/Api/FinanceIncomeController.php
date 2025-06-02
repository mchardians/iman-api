<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceIncome\StoreFinanceIncomeRequest;
use App\Http\Requests\FinanceIncome\UpdateFinanceIncomeRequest;
use App\Services\FinanceIncomeService;
use App\Http\Resources\FinanceIncomeSimpleResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FinanceIncomeController extends Controller
{
    private $financeIncomeService;
    public function __construct(FinanceIncomeService $financeIncomeService) {
        $this->financeIncomeService = $financeIncomeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return ApiResponse::success([
                "finance_incomes" => FinanceIncomeSimpleResource::collection($this->financeIncomeService->getAllFinanceIncomes())
            ],
                "Successfully fetched all finance incomes!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch finance incomes. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinanceIncomeRequest $request)
    {
        try {
            return ApiResponse::success([
                "finance_income" => new FinanceIncomeSimpleResource($this->financeIncomeService->createFinanceIncome($request->validated()))
            ],
                "New finance income has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while creating a new finance income!",
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
                "finance_income" => new FinanceIncomeSimpleResource($this->financeIncomeService->getFinanceIncomeById($id))
            ],
                "Successfully fetched the finance income details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested finance income was not found!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinanceIncomeRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "finance_income" => new FinanceIncomeSimpleResource($this->financeIncomeService->updateFinanceIncome($id, $request->validated()))
            ],
                "The finance income was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the finance income!",
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
                "finance_income" => new FinanceIncomeSimpleResource($this->financeIncomeService->deleteFinanceIncome($id))
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the finance income!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
