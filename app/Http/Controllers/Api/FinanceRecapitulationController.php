<?php

namespace App\Http\Controllers\Api;

use App\Filters\FinanceRecapitulationFilter;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FinanceRecapitulationCollection;
use App\Services\FinanceRecapitulationService;
use Illuminate\Http\Request;
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
    public function __invoke(Request $request, FinanceRecapitulationFilter $financeRecapitulationFilter)
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
                        $this->financeRecapitulationService->getFinanceRecapitulationTotals($queryParameters)
                    ),
                    "Successfully fetched all finance recapitulations!"
                    );
                }
            }

            return ApiResponse::success(new FinanceRecapitulationCollection(
                $this->financeRecapitulationService->getAllFinanceRecapitulations($queryParameters),
                $this->financeRecapitulationService->getFinanceRecapitulationTotals()
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
}
