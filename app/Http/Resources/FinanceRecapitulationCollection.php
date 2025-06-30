<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FinanceRecapitulationCollection extends ResourceCollection
{
    protected $totals;

    public function __construct($resource, ?object $totals = null) {
        parent::__construct($resource);
        $this->totals = $totals;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "finance_recapitulations" => FinanceRecapitulationSimpleResource::collection($this->collection),
            $this->merge(($request->filled("pagination") && (bool)$request->input("pagination") === true) ? [
                "meta" => [
                    "total" => $this->total(),
                    "per_page" => $this->perPage(),
                    "current_page" => $this->currentPage(),
                    "last_page" => $this->lastPage(),
                    "first_page_url" => $this->url(1),
                    "last_page_url" => $this->url($this->lastPage()),
                    "next_page_url" => $this->nextPageUrl(),
                    "prev_page_url" => $this->previousPageUrl(),
                    "path" => $this->path(),
                    "from" => $this->firstItem(),
                    "to" => $this->lastItem(),
                ],
                "finance_accumulations" => [
                    "current_page" => [
                        "total_income" => "Rp. ". number_format($this->collection->sum("income"), 0, ',', '.'),
                        "total_expense" => "Rp. ". number_format($this->collection->sum("expense"), 0, ',', '.'),
                    ],
                    "overall" => [
                        "total_income" => "Rp. ". number_format($this->totals->total_income, 0, ',', '.'),
                        "total_expense" => "Rp. ". number_format($this->totals->total_income, 0, ',', '.'),
                    ]
                ],
            ] : [
                "finance_accumulations" => [
                    "overall" => [
                        "total_income" => "Rp. ". number_format($this->totals->total_income, 0, ',', '.'),
                        "total_expense" => "Rp. ". number_format($this->totals->total_expense, 0, ',', '.'),
                    ]
                ],
            ]),
        ];
    }
}
