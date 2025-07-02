<?php

namespace App\Http\Controllers\Api;

use App\Filters\NewsFilter;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsSimpleResource;
use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Http\Resources\NewsCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, NewsFilter $newsFilter)
    {
        try {
            $queryParameters = $newsFilter->transform($request);

            if($request->filled("pagination")) {
                $isPaginated = $request->input("pagination");
                $pageSize = null;

                if($request->filled("page_size")) {
                    $pageSize = $request->input("page_size");
                }

                if($isPaginated) {
                    return ApiResponse::success(
                        new NewsCollection(
                            $this->newsService->
                            getAllPaginatedNews($pageSize, $queryParameters)
                            ->appends($request->query())
                        ),
                        "Successfully fetched all news items!",
                        200
                    );
                }
            }

           return ApiResponse::success([
                "news" => NewsSimpleResource::collection(
                    $this->newsService->getAllNews($queryParameters)
                )
            ],
                "Successfully fetched all news items!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch news items. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    public function getPublishedNews(Request $request, NewsFilter $newsFilter) {
        try {
            $queryParameters = $newsFilter->transform($request);

            return ApiResponse::success([
                "news" => new NewsSimpleResource($this->newsService->getAllPublishedNews($queryParameters))
            ],
                "Successfully fetched all published news items!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch news items. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            return ApiResponse::success([
                "news" => new NewsSimpleResource($this->newsService->getNewsBySlug($slug))
            ],
                "Successfully fetched the news item details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested news item was not found!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        try {
            return ApiResponse::success([
                "news" => new NewsSimpleResource($this->newsService->createNews($request->validated()))
            ],
                "A news item has been created successfully",
                201
            );
        } catch (HttpException $e) {
           return APiResponse::error(
               "An error occurred while creating a new news item!",
               $e->getMessage(),
               $e->getStatusCode()
           );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "news" => new NewsSimpleResource($this->newsService->updateNews($id, $request->validated()))
            ],
                "A news item has been updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the news!",
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
                "news" => new NewsSimpleResource($this->newsService->deleteNews($id))
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the news!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    public function setNewsStatus(UpdateNewsRequest $request, string $id) {
        try {
            return ApiResponse::success([
                "news" => new NewsSimpleResource(
                    $this->newsService->setNewsStatus($id, $request->validated())
                )
            ],
                "A news item's status has been updated successfully",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the news item's status!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
