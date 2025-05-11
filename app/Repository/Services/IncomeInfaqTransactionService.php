<?php

namespace App\Repository\Services;

use App\Libraries\CodeGeneration;
use App\Models\IncomeInfaqTransaction;
use App\Repository\Interfaces\IncomeInfaqTransactionInterface;

class IncomeInfaqTransactionService implements IncomeInfaqTransactionInterface {

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        $codeGeneration = new CodeGeneration(IncomeInfaqTransaction::class, "transaction_code", "ICM");
        $data['transaction_code'] = $codeGeneration->getGeneratedCode();

        IncomeInfaqTransaction::create($data);

        return response()->json([
            "success" => true,
            "message" => "Transaksi pemasukan infaq berhasil ditambahkan!"
        ], 201);
    }

    /**
     * @inheritDoc
     */
    public function delete($id) {
        IncomeInfaqTransaction::findOrFail($id)->delete();

        return response()->json([
            "success" => true,
            "message" => "Transaksi pemasukan infaq berhasil dihapus!"
        ], 201);
    }

    /**
     * @inheritDoc
     */
    public function getAll() {
        $incomeInfaqTransactions = IncomeInfaqTransaction::all();

        return response()->json([
            "success" => true,
            "data" => $incomeInfaqTransactions
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function getById($id) {
        $incomeInfaqTransaction = IncomeInfaqTransaction::findOrFail($id);

        return response()->json([
            "success" => true,
            "message" => $incomeInfaqTransaction
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function update(array $data, $id) {
        IncomeInfaqTransaction::findOrFail($id)->update($data);

        return response()->json([
            "success" => true,
            "message" => "Transaksi pemasukan infaq berhasil dirubah!"
        ], 200);
    }
}