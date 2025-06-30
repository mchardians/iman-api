<?php

namespace App\Filters;

class FinanceRecapitulationFilter extends ApiFilter {
    protected $safeParams = [
        "date_range" => ["between"]
    ];

    protected $columnMap = [
        "date_range" => "date"
    ];

    protected $operatorMap = [
        "between" => "between",
    ];
}