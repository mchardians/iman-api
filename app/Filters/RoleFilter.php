<?php

namespace App\Filters;
use App\Filters\ApiFilter;

class RoleFilter extends ApiFilter {
    protected $safeParams = [
        "name" => ["eq", "neq", "lk"]
    ];

    protected $operatorMap = [
        "eq" => "=",
        "neq" => "!=",
        "lk" => "like"
    ];
}