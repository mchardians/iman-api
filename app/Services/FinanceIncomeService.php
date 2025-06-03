<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\FinanceIncomeContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FinanceIncomeService
{
    public function __construct(private FinanceIncomeContract $financeIncomeRepository)
    {
        $this->financeIncomeRepository = $financeIncomeRepository;
    }

    public function getAllFinanceIncomes() {
        return $this->financeIncomeRepository->all();
    }

    public function getFinanceIncomeById(string $id) {
        try {
            $financeIncome = $this->financeIncomeRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $financeIncome;
    }

    public function createFinanceIncome(array $data) {
        if(isset($data["transaction_receipt"]) && $data["transaction_receipt"] instanceof \Illuminate\Http\UploadedFile) {
            $data["transaction_receipt"] = "storage/". $this->uploadFile($data["transaction_receipt"]);
        }

        return $this->financeIncomeRepository->create($data);
    }

    public function updateFinanceIncome(string $id, array $data) {
        $financeIncome = $this->getFinanceIncomeById($id);

        if(isset($data["transaction_receipt"]) && $data["transaction_receipt"] instanceof \Illuminate\Http\UploadedFile) {
            $receiptPath = str_replace("storage/", "", $financeIncome->transaction_receipt);

            if(!empty($financeIncome->transaction_receipt) && Storage::disk('public')->exists(path: $receiptPath)) {
                Storage::disk('public')->delete($receiptPath);
            }

            $data["transaction_receipt"] = "storage/". $this->uploadFile($data["transaction_receipt"]);
        }

        try {
            return $this->financeIncomeRepository->update($id, $data) === true ? $financeIncome->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteFinanceIncome(string $id) {
        $financeIncome = $this->getFinanceIncomeById($id);

        try {
            $receiptPath = str_replace("storage/", "", $financeIncome->transaction_receipt);

            if(!empty($financeIncome->transaction_receipt) && Storage::disk('public')->exists(path: $receiptPath)) {
                Storage::disk('public')->delete($receiptPath);
            }
            
            return $this->financeIncomeRepository->delete($id) === true ? $financeIncome : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function uploadFile($transactionReceipt) {
        $receiptName = $transactionReceipt->hashName();

        return $transactionReceipt->storePubliclyAs("income-receipts", $receiptName, "public");
    }
}