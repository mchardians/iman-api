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
            $financeIncome = $this->financeExpenseRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $financeIncome;
    }

    public function createFinanceExpense(array $data) {
        if(isset($data["transaction_receipt"]) && $data["transaction_receipt"] instanceof \Illuminate\Http\UploadedFile) {
            $data["transaction_receipt"] = "storage/". $this->uploadFile($data["transaction_receipt"]);
        }

        return $this->financeExpenseRepository->create($data);
    }

    public function updateFinanceExpense(string $id, array $data) {
        $financeIncome = $this->getFinanceExpenseById($id);

        if(isset($data["transaction_receipt"]) && $data["transaction_receipt"] instanceof \Illuminate\Http\UploadedFile) {
            if(!empty($financeIncome->transaction_receipt) && Storage::disk('public')->exists(path: $financeIncome->transaction_receipt)) {
                Storage::disk('public')->delete($financeIncome->transaction_receipt);
            }

            $data["transaction_receipt"] = "storage/". $this->uploadFile($data["transaction_receipt"]);
        }

        try {
            return $this->financeExpenseRepository->update($id, $data) === true ? $financeIncome->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteFinanceExpense(string $id) {
        $financeIncome = $this->getFinanceExpenseById($id);

        try {
            return $this->financeExpenseRepository->delete($id) === true ? $financeIncome : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function uploadFile($transactionReceipt) {
        $receiptName = $transactionReceipt->hashName();

        return $transactionReceipt->storePubliclyAs("income-expenses", $receiptName, "public");
    }
}