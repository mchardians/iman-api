<?php

namespace App\Repositories\Contracts;

interface FinanceRecapitulationContract
{
    public function all(array $filters = []);
    public function paginate(?string $perPage = null, array $filters = []);
    public function getFinanceTotals(array $filters = []);
}