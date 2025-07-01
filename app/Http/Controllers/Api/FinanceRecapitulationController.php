<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Filters\FinanceRecapitulationFilter;
use App\Services\FinanceRecapitulationService;
use App\Http\Resources\FinanceRecapitulationCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FinanceRecapitulationController extends Controller
{
    private $financeRecapitulationService;

    public function __construct(FinanceRecapitulationService $financeRecapitulationService) {
        $this->financeRecapitulationService = $financeRecapitulationService;
    }

    /**
     * Handle the incoming request.
     */
    public function index(Request $request, FinanceRecapitulationFilter $financeRecapitulationFilter)
    {
        try {
            $queryParameters = $financeRecapitulationFilter->transform($request);

            if($request->filled("pagination")) {
                $isPaginated = $request->input("pagination");
                $pageSize = null;

                if($request->filled("page_size")) {
                    $pageSize = $request->input("page_size");
                }

                if($isPaginated) {
                    return ApiResponse::success(new FinanceRecapitulationCollection(
                $this->financeRecapitulationService
                        ->getAllPaginatedFinanceRecapitulations($pageSize, $queryParameters)
                        ->appends($request->query()),
                        $this->financeRecapitulationService->getFinanceAccumulations($queryParameters)
                    ),
                    "Successfully fetched all finance recapitulations!"
                    );
                }
            }

            return ApiResponse::success(new FinanceRecapitulationCollection(
                $this->financeRecapitulationService->getAllFinanceRecapitulations($queryParameters),
                $this->financeRecapitulationService->getFinanceAccumulations()
            ),
                "Successfully fetched all finance recapitulations!"
            );
        } catch (HttpException $e) {
            return ApiResponse::error(
                "Failed to fetch finance recapitulation. Please try again.",
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
    }

    public function preview(Request $request, FinanceRecapitulationFilter $financeRecapitulationFilter) {
        $queryParameters = $financeRecapitulationFilter->transform($request);

        return $this->financeRecapitulationService->previewPdfFinanceRecapitulationReport($queryParameters);
    }

    public function export(Request $request, FinanceRecapitulationFilter $financeRecapitulationFilter) {
        $queryParameters = $financeRecapitulationFilter->transform($request);

        if($request->filled("format")) {
            $format = strtolower($request->input("format"));
            return match ($format) {
                "pdf" => $this->financeRecapitulationService->downloadPdfFinanceRecapitulationReport($queryParameters),
                "xlsx" => $this->financeRecapitulationService->downloadExcelFinanceRecapitulationReport($queryParameters)
            };
        }
    }
}
