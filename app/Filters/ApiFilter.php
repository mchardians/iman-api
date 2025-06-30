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
                    match (strtolower($this->operatorMap[$operator])) {
                        "like" => (function() use(&$eloquentQueries, $column, $operator, $query) {
                            $eloquentQueries[] = [$column, $this->operatorMap[$operator], "%{$query[$operator]}%"];
                        })(),
                        "between" => (function() use(&$eloquentQueries, $column, $operator, $query) {
                            if(is_string($query[$operator]) && str_contains($query[$operator], ",")) {
                                $betweenValue = array_map("trim", explode(",", $query[$operator], 2));

                                if(count($betweenValue) === 2) {
                                    $eloquentQueries = [$column, $betweenValue];
                                }
                            }
                        })(),
                        default => $eloquentQueries[] = [$column, $this->operatorMap[$operator], $query[$operator]]
                    };
                }
            }
        }
        return $eloquentQueries;
    }
}