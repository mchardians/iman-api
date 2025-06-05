<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinanceRecapitulationSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        Carbon::setLocale("id");

        return [
            "date" => Carbon::parse($this->date)->translatedFormat("d F Y"),
            "category" => $this->financeCategory->name,
            "description" => $this->description,
            "income" => $this->income === null ? "-" : "Rp. ". number_format($this->income, 0, ',', '.'),
            "expense" => $this->expense === null ? "-" : "Rp. ". number_format($this->expense, 0, ',', '.')
        ];
    }
}
