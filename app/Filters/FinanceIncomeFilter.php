<?php

namespace App\Filters;

class FinanceIncomeFilter extends ApiFilter{
    protected $safeParams = [
        "income_transaction" => ["eq"],
        "date" => ["eq", "lk"],
        "description" => ["lk"],
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}