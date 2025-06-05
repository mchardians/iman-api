<?php

namespace App\Services;

use App\Repositories\Contracts\FinanceRecapitulationContract;

class FinanceRecapitulationService
{
    public function __construct(protected FinanceRecapitulationContract $financeRecapitulationRepository)
    {
        $this->financeRecapitulationRepository = $financeRecapitulationRepository;
    }

    public function getAllFinanceRecapitulations() {
        return $this->financeRecapitulationRepository->all();
    }

    public function getFinanceRecapitulationByParams(array $params) {
        return $this->financeRecapitulationRepository->whereEquals("date", $params);
    }
}