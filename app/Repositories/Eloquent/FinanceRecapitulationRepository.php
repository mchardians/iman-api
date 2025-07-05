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
                $startDate = now()->startOfMonth()->toDateString();
                $endDate = now()->endOfMonth()->toDateString();

                return $this->getFinanceIncomes(startDate: $startDate, endDate: $endDate)
                ->unionAll($this->getFinanceExpenses(startDate: $startDate, endDate: $endDate))
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
                $startDate = now()->startOfMonth()->toDateString();
                $endDate = now()->endOfMonth()->toDateString();

                return $this->getFinanceIncomes(startDate: $startDate, endDate: $endDate)
                ->unionAll($this->getFinanceExpenses(startDate: $startDate, endDate: $endDate))
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
        if($startDate !== null && $endDate !== null) {
            return $this->financeIncome->select("income_transaction as transaction_code", "date", "finance_category_id", "description", "amount as income", DB::raw("NULL as expense"))
            ->whereHas("financeCategory", function($query) {
                $query->where("type", "=", "income");
            })
            ->whereBetween($column, [$startDate, $endDate]);
        }

        return $this->financeIncome->select("income_transaction as transaction_code", "date", "finance_category_id", "description", "amount as income", DB::raw("NULL as expense"))
        ->whereHas("financeCategory", function($query) {
            $query->where("type", "=", "income");
        });
    }

    private function getFinanceExpenses(string $column = "date", ?string $startDate = null, ?string $endDate = null) {
        if($startDate !== null && $endDate !== null) {
            return $this->financeExpense->select("expense_transaction as transaction_code", "date", "finance_category_id", "description", DB::raw("NULL as income"), "amount as expense")
            ->whereHas("financeCategory", function($query) {
                $query->where("type", "=", "expense");
            })
            ->whereBetween($column, [$startDate, $endDate]);
        }

        return $this->financeExpense->select("expense_transaction as transaction_code", "date", "finance_category_id", "description", DB::raw("NULL as income"), "amount as expense")
        ->whereHas("financeCategory", function($query) {
            $query->where("type", "=", "expense");
        });
    }

    public function getFinanceAccumulations(array $filters = []) {
        return match (empty($filters)) {
            true => (function() {
                $startDate = now()->startOfMonth()->toDateString();
                $endDate = now()->endOfMonth()->toDateString();

                $incomeTotal = $this->getFinanceIncomes(startDate: $startDate, endDate: $endDate)
                    ->sum("amount");

                $expenseTotal = $this->getFinanceExpenses(startDate: $startDate, endDate: $endDate)
                    ->sum("amount");

                $incomeTotalPrevious = $this->getFinanceIncomes("date", null, $startDate)
                ->where("date", "<", $startDate)->sum("amount");
                $expenseTotalPrevious = $this->getFinanceExpenses("date", null, $startDate)
                ->where("date", "<", value: $startDate)->sum("amount");

                $openingBalance = $incomeTotalPrevious - $expenseTotalPrevious;
                $closingBalance = $openingBalance + ($incomeTotal - $expenseTotal);

                return (object) [
                    "total_income" => $incomeTotal,
                    "total_expense" => $expenseTotal,
                    "opening_balance" => $openingBalance,
                    "closing_balance" => $closingBalance
                ];
            })(),
            false => (function() use(&$filters) {
                [$column, [$startDate, $endDate]] = $filters;

                $incomeTotal = $this->getFinanceIncomes($column, $startDate, $endDate)
                    ->sum("amount");

                $expenseTotal = $this->getFinanceExpenses($column, $startDate, $endDate)
                    ->sum("amount");

                $incomeTotalPrevious = $this->getFinanceIncomes($column)->where($column, "<", $startDate)
                ->sum("amount");
                $expenseTotalPrevious = $this->getFinanceExpenses($column)->where($column, "<", $startDate)
                ->sum(column: "amount");

                $openingBalance = $incomeTotalPrevious - $expenseTotalPrevious;
                $closingBalance = $openingBalance + ($incomeTotal - $expenseTotal);

                return (object) [
                    "total_income" => $incomeTotal,
                    "total_expense" => $expenseTotal,
                    "opening_balance" => $openingBalance,
                    "closing_balance" => $closingBalance
                ];
            })(),
        };
    }
}