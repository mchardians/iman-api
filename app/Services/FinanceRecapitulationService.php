<?php

namespace App\Services;

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

    public function getFinanceRecapitulationTotals(array $filters = []) {
        return $this->financeRecapitulationRepository->getFinanceTotals($filters);
    }
}