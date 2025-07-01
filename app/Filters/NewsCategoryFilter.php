<?php

namespace App\Filters;

class NewsCategoryFilter extends ApiFilter {
    protected $safeParams = [
        "name" => ["eq", "lk"]
    ];

    protected $operatorMap = [
        "eq" => "=",
        "lk" => "like"
    ];
}