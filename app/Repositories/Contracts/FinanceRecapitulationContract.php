<?php

namespace App\Repositories\Contracts;

interface FinanceRecapitulationContract
{
    public function all();
    public function whereEquals(string $column, array $values);
}