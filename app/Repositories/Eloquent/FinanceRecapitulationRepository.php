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
    public function all(array $filters = []) {
        return match (empty($filters)) {
            true => (function() {
                return $this->getFinanceIncomes()
                ->unionAll($this->getFinanceExpenses())
                ->orderByDesc("date")
                ->get();
            })(),
            false => (function() use(&$filters) {
                [$column, [$startDate, $endDate]] = $filters;

                return $this->getFinanceIncomes($column, $startDate, $endDate)
                ->unionAll($this->getFinanceExpenses($column, $startDate, $endDate))
                ->orderByDesc("date")
                ->get();
            })(),
        };
    }

    /**
     * @inheritDoc
     */
    public function paginate(string|null $perPage = null, array $filters = []) {
        return match (empty($filters)) {
            true => (function() use(&$perPage) {
                return $this->getFinanceIncomes()
                ->unionAll($this->getFinanceExpenses())
                ->orderByDesc("date")
                ->paginate($perPage);
            })(),
            false => (function() use(&$perPage, &$filters){
                [$column, [$startDate, $endDate]] = $filters;

                return $this->getFinanceIncomes($column, $startDate, $endDate)
                ->unionAll($this->getFinanceExpenses($column, $startDate, $endDate))
                ->orderByDesc("date")
                ->paginate($perPage);
            })(),
        };
    }

    private function getFinanceIncomes(string $column = "date", ?string $startDate = null, ?string $endDate = null) {
        if($startDate !== null && !$endDate !== null) {
            return $this->financeIncome->select("date", "finance_category_id", "description", "amount as income", DB::raw("NULL as expense"))
            ->whereHas("financeCategory", function($query) {
                $query->where("type", "=", "income");
            })
            ->whereBetween($column, [$startDate, $endDate]);
        }

        return $this->financeIncome->select("date", "finance_category_id", "description", "amount as income", DB::raw("NULL as expense"))
        ->whereHas("financeCategory", function($query) {
            $query->where("type", "=", "income");
        });
    }

    private function getFinanceExpenses(string $column = "date", ?string $startDate = null, ?string $endDate = null) {
        if($startDate !== null && !$endDate !== null) {
            return $this->financeExpense->select("date", "finance_category_id", "description", DB::raw("NULL as income"), "amount as expense")
            ->whereHas("financeCategory", function($query) {
                $query->where("type", "=", "expense");
            })
            ->whereBetween($column, [$startDate, $endDate]);
        }

        return $this->financeExpense->select("date", "finance_category_id", "description", DB::raw("NULL as income"), "amount as expense")
        ->whereHas("financeCategory", function($query) {
            $query->where("type", "=", "expense");
        });
    }

    public function getFinanceTotals(array $filters = []) {
        return match (empty($filters)) {
            true => (function() {
                $incomeTotal = $this->getFinanceIncomes()
                    ->sum("amount");

                $expenseTotal = $this->getFinanceExpenses()
                    ->sum("amount");

                return (object) [
                    "total_income" => $incomeTotal,
                    "total_expense" => $expenseTotal
                ];
            })(),
            false => (function() use(&$filters) {
                [$column, [$startDate, $endDate]] = $filters;

                $incomeTotal = $this->getFinanceIncomes($column, $startDate, $endDate)
                    ->sum("amount");

                $expenseTotal = $this->getFinanceExpenses($column, $startDate, $endDate)
                    ->sum("amount");

                return (object) [
                    "total_income" => $incomeTotal,
                    "total_expense" => $expenseTotal
                ];
        })(),
    };
    }
}