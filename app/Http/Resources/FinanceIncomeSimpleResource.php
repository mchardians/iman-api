<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinanceIncomeSimpleResource extends JsonResource
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
            "id" => $this->id,
            "income_transaction" => $this->income_transaction,
            "date" => Carbon::parse($this->date)->format("Y-m-d"),
            "date_idn_format" => Carbon::parse($this->date)->translatedFormat("d F Y"),
            "finance_category" => new FinanceCategorySimpleResource($this->financeCategory),
            "description" => $this->description,
            "amount" => $this->amount,
            "amount_idn_format" => "Rp. ". number_format($this->amount, 0, ',', '.'),
            "transaction_receipt" => $this->transaction_receipt ? asset($this->transaction_receipt) : null,
            "created_at" => Carbon::parse($this->created_at)->translatedFormat("d F Y H:i"),
            "created_at_human" => $this->created_at->diffforhumans()
        ];
    }
}
