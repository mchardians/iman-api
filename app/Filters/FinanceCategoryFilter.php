<?php

namespace App\Filters;

class FinanceCategoryFilter extends ApiFilter{
    protected $safeParams = [
        "name" => ["eq", "lk"],
        "type" => ["eq"],
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}