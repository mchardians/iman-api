<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Filters\NewsCategoryFilter;
use App\Http\Controllers\Controller;
use App\Services\NewsCategoryService;
use App\Http\Resources\NewsCategorySimpleResource;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Requests\NewsCategory\StoreNewsCategoryRequest;
use App\Http\Requests\NewsCategory\UpdateNewsCategoryRequest;
use App\Http\Resources\NewsCategoryCollection;

class NewsCategoryController extends Controller
{
    private $newsCategoryService;

    public function __construct(NewsCategoryService $newsCategoryService)
    {
        $this->newsCategoryService = $newsCategoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, NewsCategoryFilter $newsCategoryFilter)
    {
        try {
            $queryParameters = $newsCategoryFilter->transform($request);

            if($request->filled("pagination")) {
                $isPaginated = $request->input("pagination");
                $pageSize = null;

                if($request->filled("page_size")) {
                    $pageSize = $request->input("page_size");
                }

                if($isPaginated) {
                    return ApiResponse::success(
                            new NewsCategoryCollection(
                            $this->newsCategoryService
                            ->getAllPaginatedNewsCategories($pageSize, $queryParameters)
                            ->appends($request->query())
                        ),
                        "Successfully fetched all news categories!",
                        200
                    );
                }
            }

           return ApiResponse::success([
                "news_categories" => NewsCategorySimpleResource::collection(
                    $this->newsCategoryService->getAllNewsCategories($queryParameters)
                )
            ],
                "Successfully fetched all news categories!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch news categories. Please try again.",
                $e->getMessage(),
                $e->getStatusCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsCategoryRequest $request)
    {
        try {
            return ApiResponse::success([
                "news_category" => new NewsCategorySimpleResource(
                    $this->newsCategoryService->createNewsCategory($request->validated())
                    )
            ],
                "New news category has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return APiResponse::error(
               "An error occurred while creating a new news category!",
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
                "news_category" => new NewsCategorySimpleResource($this->newsCategoryService->getNewsCategoryById($id))
            ],
                "Successfully fetched the news category details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested news category was not found!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsCategoryRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "news_category" => new NewsCategorySimpleResource($this->newsCategoryService->updateNewsCategory($id, $request->validated()))
            ],
                "The news category was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the news category!",
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
                "news_category" => new NewsCategorySimpleResource($this->newsCategoryService->deleteNewsCategory($id))
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the news category!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
