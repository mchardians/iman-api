<?php

namespace App\Repositories\Eloquent;

use App\Models\FinanceIncome;
use App\Models\FinanceExpense;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\FinanceRecapitulationContract;

class FinanceRecapitulationRepository implements FinanceRecapitulationContract
{
    protected $financeIncome;
    protected $financeExpense;

    public function __construct(FinanceIncome $financeIncome, FinanceExpense $financeExpense) {
        $this->financeIncome = $financeIncome;
        $this->financeExpense = $financeExpense;
    }

    /**
     * @inheritDoc
     */
    public function all() {
        return $this->getFinanceIncomes()->unionAll($this->getFinanceExpenses())->orderByDesc("date")->get();
    }

    /**
     * @inheritDoc
     */
    public function whereEquals(string $column, array $values) {
        [$startDate, $endDate] = $values;
        return $this->getFinanceIncomes($startDate, $endDate)
        ->unionAll($this->getFinanceExpenses($startDate, $endDate))
        ->orderByDesc($column)
        ->get();
    }

    private function getFinanceIncomes(?string $startDate = null, ?string $endDate = null) {
        if($startDate !== null && !$endDate !== null) {
            return $this->financeIncome->select("date", "finance_category_id", "description", "amount as income", DB::raw("NULL as expense"))
            ->whereHas("financeCategory", function($query) {
                $query->where("type", "=", "income");
            })
            ->whereBetween("date", [$startDate, $endDate]);
        }

        return $this->financeIncome->select("date", "finance_category_id", "description", "amount as income", DB::raw("NULL as expense"))
        ->whereHas("financeCategory", function($query) {
            $query->where("type", "=", "income");
        });
    }

    private function getFinanceExpenses(?string $startDate = null, ?string $endDate = null) {
        if($startDate !== null && !$endDate !== null) {
            return $this->financeExpense->select("date", "finance_category_id", "description", DB::raw("NULL as income"), "amount as expense")
            ->whereHas("financeCategory", function($query) {
                $query->where("type", "=", "expense");
            })
            ->whereBetween("date", [$startDate, $endDate]);
        }

        return $this->financeExpense->select("date", "finance_category_id", "description", DB::raw("NULL as income"), "amount as expense")
        ->whereHas("financeCategory", function($query) {
            $query->where("type", "=", "expense");
        });
    }
}