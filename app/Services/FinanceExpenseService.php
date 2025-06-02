<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\FinanceExpenseContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FinanceExpenseService
{
    public function __construct(protected FinanceExpenseContract $financeExpenseRepository)
    {
        $this->financeExpenseRepository = $financeExpenseRepository;
    }

    public function getAllFinanceExpenses() {
        return $this->financeExpenseRepository->all();
    }

    public function getFinanceExpenseById(string $id) {
        try {
            $financeExpense = $this->financeExpenseRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $financeExpense;
    }

    public function createFinanceExpense(array $data) {
        if(isset($data["transaction_receipt"]) && $data["transaction_receipt"] instanceof \Illuminate\Http\UploadedFile) {
            $data["transaction_receipt"] = "storage/". $this->uploadFile($data["transaction_receipt"]);
        }

        return $this->financeExpenseRepository->create($data);
    }

    public function updateFinanceExpense(string $id, array $data) {
        $financeExpense = $this->getFinanceExpenseById($id);

        if(isset($data["transaction_receipt"]) && $data["transaction_receipt"] instanceof \Illuminate\Http\UploadedFile) {
            if(!empty($financeExpense->transaction_receipt) && Storage::disk('public')->exists(path: $financeExpense->transaction_receipt)) {
                Storage::disk('public')->delete($financeExpense->transaction_receipt);
            }

            $data["transaction_receipt"] = "storage/". $this->uploadFile($data["transaction_receipt"]);
        }

        try {
            return $this->financeExpenseRepository->update($id, $data) === true ? $financeExpense->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteFinanceExpense(string $id) {
        $financeExpense = $this->getFinanceExpenseById($id);

        try {
            return $this->financeExpenseRepository->delete($id) === true ? $financeExpense : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function uploadFile($transactionReceipt) {
        $receiptName = $transactionReceipt->hashName();

        return $transactionReceipt->storePubliclyAs("expense-receipt", $receiptName, "public");
    }
}