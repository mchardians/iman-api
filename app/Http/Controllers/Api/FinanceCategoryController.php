<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceCategory\StoreFinanceCategoryRequest;
use App\Http\Requests\FinanceCategory\UpdateFinanceCategoryRequest;
use App\Http\Resources\FinanceCategorySimpleResource;
use App\Services\FinanceCategoryService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FinanceCategoryController extends Controller
{
    private $financeCategoryService;

    public function __construct(FinanceCategoryService $financeCategoryService) {
        $this->financeCategoryService = $financeCategoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if($request->has("type")) {
                $param = $request->query("type");

                return ApiResponse::success([
                    "finance_categories" => FinanceCategorySimpleResource::collection($this->financeCategoryService->getFinanceCategoryByParam($param))
                ],
                    "Successfully filter finance categories by {$param} type!",
                    200
                );
            }

            return ApiResponse::success([
                "finance_categories" => FinanceCategorySimpleResource::collection($this->financeCategoryService->getAllFinanceCategories())
            ],
                "Successfully fetched all finance categories!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch finance categories. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinanceCategoryRequest $request)
    {
        try {
            return ApiResponse::success([
                "finance_category" => new FinanceCategorySimpleResource($this->financeCategoryService->createFinanceCategory($request->validated()))
            ],
                "New finance category has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while creating a new finance category!",
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
                "finance_category" => new FinanceCategorySimpleResource($this->financeCategoryService->getFinanceCategoryById($id))
            ],
                "Successfully fetched the finance category details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested finance category was not found!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinanceCategoryRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "finance_category" => new FinanceCategorySimpleResource($this->financeCategoryService->updateFinanceCategory($id, $request->validated()))
            ],
                "The finance category was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the finance category!",
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
                "finance_category" => new FinanceCategorySimpleResource($this->financeCategoryService->deleteFinanceCategory($id))
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the finance category!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
