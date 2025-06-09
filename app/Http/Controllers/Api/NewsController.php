<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsSimpleResource;
use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
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
    public function index(Request $request)
    {
        try {
            if($request->filled("status")) {
                $status = $request->query("status");

                if(in_array($status, ["drafted", "published", "archived"])) {
                    return ApiResponse::success([
                        "news" => NewsSimpleResource::collection($this->newsService->getNewsByParam($status))
                    ],
                        "Successfully fetched all news by {$status} status!",
                        200
                    );
                }
            }

           return ApiResponse::success([
                "news" => NewsSimpleResource::collection($this->newsService->getAllNews())
            ],
                "Successfully fetched all news!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch news. Please try again.",
                $e->getMessage(),
                $e->getStatusCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return ApiResponse::success([
                "news" => new NewsSimpleResource($this->newsService->getNewsById($id))
            ],
                "Successfully fetched the news details!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "The requested news was not found!",
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
                "New role has been created successfully!",
                201
            );
        } catch (HttpException $e) {
           return APiResponse::error(
               "An error occurred while creating a new role!",
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
                "The news was updated successfully!",
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

    public function publish(string $id) {
        try {
            return ApiResponse::success([
                "news" => new NewsSimpleResource($this->newsService->publishNews($id))
            ],
                "The news was published successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while publishing the news!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    public function archive(string $id) {
        try {
            return ApiResponse::success([
                "news" => new NewsSimpleResource($this->newsService->archiveNews($id))
            ],
                "The news was archived successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while archiving the news!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
