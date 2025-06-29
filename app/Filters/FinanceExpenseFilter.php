<?php

namespace App\Filters;

class FinanceExpenseFilter extends ApiFilter {
    protected $safeParams = [
        "expense_transaction" => ["eq"],
        "date" => ["eq", "lk"],
        "description" => ["lk"],
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}