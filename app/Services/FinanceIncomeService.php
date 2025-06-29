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

    public function getAllFinanceIncomes(array $filters = []) {
        return $this->financeIncomeRepository->all($filters);
    }

    public function getAllPaginatedFinanceIncomes(?string $pageSize = null, array $filters = []) {
        return $this->financeIncomeRepository->paginate($pageSize, $filters);
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
        try {
            $financeIncome = $this->getFinanceIncomeById($id);

            if(isset($data["transaction_receipt"]) && $data["transaction_receipt"] instanceof \Illuminate\Http\UploadedFile) {
                $receiptPath = str_replace("storage/", "", $financeIncome->transaction_receipt);

                if(!empty($financeIncome->transaction_receipt) && Storage::disk('public')->exists(path: $receiptPath)) {
                    Storage::disk('public')->delete($receiptPath);
                }

                $data["transaction_receipt"] = "storage/". $this->uploadFile($data["transaction_receipt"]);
            }
            return $this->financeIncomeRepository->update($id, $data) === true ? $financeIncome->fresh() : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteFinanceIncome(string $id) {
        try {
            $financeIncome = $this->getFinanceIncomeById($id);
            $isDeleted = $this->financeIncomeRepository->delete($id);

            if($isDeleted) {
                $receiptPath = str_replace("storage/", "", $financeIncome->transaction_receipt);

                if(!empty($financeIncome->transaction_receipt) && Storage::disk('public')->exists(path: $receiptPath)) {
                    Storage::disk('public')->delete($receiptPath);
                }
            }

            return $isDeleted === true ? $financeIncome : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function uploadFile($transactionReceipt) {
        $receiptName = $transactionReceipt->hashName();

        return $transactionReceipt->storePubliclyAs("income-receipts", $receiptName, "public");
    }
}