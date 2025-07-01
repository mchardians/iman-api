<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinanceRecapitulationExcel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Repositories\Contracts\FinanceRecapitulationContract;

class FinanceRecapitulationService
{
    public function __construct(protected FinanceRecapitulationContract $financeRecapitulationRepository)
    {
        $this->financeRecapitulationRepository = $financeRecapitulationRepository;
    }

    public function getAllFinanceRecapitulations(array $filters = []) {
        return $this->financeRecapitulationRepository->all($filters);
    }

    public function getAllPaginatedFinanceRecapitulations(?string $pageSize = null, array $filters = []) {
        return $this->financeRecapitulationRepository->paginate($pageSize, $filters);
    }

    public function getFinanceAccumulations(array $filters = []) {
        return $this->financeRecapitulationRepository->getFinanceAccumulations($filters);
    }

    public function previewPdfFinanceRecapitulationReport(array $filters = []) {
        try {
            return $this->domPdfGenerator($filters)->stream();
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function downloadPdfFinanceRecapitulationReport(array $filters = []) {
        try {
            return $this->domPdfGenerator($filters)->download("Laporan Rekapitulasi Keuangan ". now()->format("d-m-Y"). ".pdf");
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function downloadExcelFinanceRecapitulationReport(array $filters = []) {
        $financeRecapitulations = $this->financeRecapitulationRepository->all($filters);
        $financeAccumulation = $this->getFinanceAccumulations($filters);
        $datePeriod = empty($filters[1]) === true ? $this->getDatePeriod() : $this->getDatePeriod(...$filters[1]);

        return Excel::download(
            new FinanceRecapitulationExcel(
                $financeRecapitulations,$financeAccumulation, $datePeriod
            ),
                "Laporan Rekapitulasi Keuangan ". now()->format("d-m-Y"). ".xlsx"
        );
    }

    private function domPdfGenerator(array $filters = []) {
        try {
            $financeRecapitulations = $this->financeRecapitulationRepository->all($filters);
            $financeAccumulation = $this->getFinanceAccumulations($filters);
            $datePeriod = empty($filters[1]) === true ? $this->getDatePeriod() : $this->getDatePeriod(...$filters[1]);

            return $pdf = Pdf::loadView(
                "pages.finance-recapitulation.export-pdf",
                compact("financeRecapitulations", "datePeriod", "financeAccumulation")
            )->setPaper("A4");
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    private function getDatePeriod(?string $startDate = null, ?string $endDate = null): object
    {
        $start = !empty($startDate)
            ? Carbon::parse($startDate)
            : Carbon::now()->startOfMonth();

        $end = !empty($endDate)
            ? Carbon::parse($endDate)
            : Carbon::now()->endOfMonth();

        return (object) [
            "startDate" => $start->translatedFormat('d F Y'),
            "endDate" => $end->translatedFormat('d F Y')
        ];
    }
}