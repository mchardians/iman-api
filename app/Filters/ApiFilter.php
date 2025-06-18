<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter {
    protected $safeParams = [];
    protected $columnMap = [];
    protected $operatorMap = [];

    public function transform(Request $request) {
        $eloquentQueries = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            if(!$request->has($param) || !is_array($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if(isset($query[$operator])) {
                    if(strtolower($this->operatorMap[$operator]) === "like") {
                        $query[$operator] = "%{$query[$operator]}%";
                    }

                    $eloquentQueries[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }
        return $eloquentQueries;
    }
}