<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FinanceRecapitulationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "finance_recapitulations" => FinanceRecapitulationSimpleResource::collection($this->collection),
            "total_income" => "Rp. ". number_format($this->collection->sum("income"), 0, ',', '.'),
            "total_expense" => "Rp. ". number_format($this->collection->sum("expense"), 0, ',', '.')
        ];
    }
}
