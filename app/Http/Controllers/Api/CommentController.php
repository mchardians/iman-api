<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentSimpleResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommentController extends Controller
{
    private $commentService;
    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        try {
            return ApiResponse::success([
                "comments" => CommentSimpleResource::collection(
                    $this->commentService->getAllNewsComments($id)
                )
            ],
                "Successfully fetched all news comments!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch all news comments. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        try {
            return ApiResponse::success([
                "comment" => new CommentSimpleResource(
                    $this->commentService->createOrReplyComment($request->validated())
                )
            ],
                "New comment has been created successfully!",
                201
            );
        } catch (HttpException $e) {
            return APiResponse::error(
               "An error occurred while creating a new comment!",
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "comment" => new CommentSimpleResource(
                    $this->commentService->updateNewsComment($id, $request->validated()
                ))
            ],
                "The comment was updated successfully!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while updating the comment!",
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
                "comment" => new CommentSimpleResource(
                    $this->commentService->deleteNewsComment($id)
                )
            ],
                "The record was successfully deleted!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "An error occurred while deleting the comment!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
