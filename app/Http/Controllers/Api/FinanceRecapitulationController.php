<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FinanceRecapitulationCollection;
use App\Http\Resources\FinanceRecapitulationSimpleResource;
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
    public function __invoke(Request $request)
    {
        try {
            if($request->has("start_date") && $request->has("end_date")) {
                $params = [$request->query("start_date"), $request->query("end_date")];

                return ApiResponse::success(new FinanceRecapitulationCollection(
                        $this->financeRecapitulationService->getFinanceRecapitulationByParams($params)
                    ),
                    "Successfully filter finance recapitulations by date from {$params[0]} until {$params[1]}!",
                    200
                );
            }

            return ApiResponse::success(new FinanceRecapitulationCollection(
                $this->financeRecapitulationService->getAllFinanceRecapitulations()
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
