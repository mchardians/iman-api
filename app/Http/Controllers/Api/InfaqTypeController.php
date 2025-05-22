<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Services\InfaqTypeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\InfaqTypeCollection;
use App\Http\Requests\InfaqType\StoreInfaqTypeRequest;
use App\Http\Requests\InfaqType\UpdateInfaqTypeRequest;
use App\Http\Resources\InfaqTypeSimpleResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InfaqTypeController extends Controller
{
    private $infaqTypeService;

    public function __construct(InfaqTypeService $infaqTypeService)
    {
        $this->infaqTypeService = $infaqTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::success(
            new InfaqTypeCollection($this->infaqTypeService->getAllInfaqTypes()),
            "Berhasil mendapatkan data tipe infaq!",
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInfaqTypeRequest $request)
    {
        try {
            return ApiResponse::success([
                "infaq_type" => new InfaqTypeSimpleResource($this->infaqTypeService->createInfaqType($request->validated()))
            ],
                "Berhasil menambahkan tipe infaq baru!",
                201
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal menambahkan tipe infaq baru!",
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
                "infaq_type" => new InfaqTypeSimpleResource($this->infaqTypeService->getInfaqTypeById($id))
            ],
                "Tipe infaq yang dicari berhasil ditemukan!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Tipe infaq yang dicari tidak ditemukan!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInfaqTypeRequest $request, string $id)
    {
        try {
            return ApiResponse::success([
                "infaq_type" => new InfaqTypeSimpleResource($this->infaqTypeService->updateInfaqType($id, $request->validated()))
            ],
                "Berhasil mengubah tipe infaq!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal mengubah tipe infaq!",
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
                "infaq_type" => new InfaqTypeSimpleResource($this->infaqTypeService->deleteInfaqType($id))
            ],
                "Berhasil menghapus tipe infaq!",
                200
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Gagal mengubah tipe infaq!",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }
}
