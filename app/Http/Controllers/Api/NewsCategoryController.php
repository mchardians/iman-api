<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsCategoryRequest;
use App\Http\Requests\UpdateNewsCategoryRequest;
use App\Repository\Services\NewsCategoryService;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
    public function index()
    {
        try {
            return $newsCategory = $this->newsCategoryService->getAll();
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
    public function store(StoreNewsCategoryRequest $request)
    {
        try {
            return $this->newsCategoryService->create($request->validated());
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
            return $this->newsCategoryService->getById($id);
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
    public function update(UpdateNewsCategoryRequest $request, string $id)
    {
        try {
            return $this->newsCategoryService->update($request->validated(), $id);
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
            return $this->newsCategoryService->delete($id);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
