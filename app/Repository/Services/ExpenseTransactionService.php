<?php

namespace App\Repository\Services;

use App\Libraries\CodeGeneration;
use App\Models\ExpenseTransaction;
use App\Repository\Interfaces\ExpenseTransactionInterface;

class ExpenseTransactionService implements ExpenseTransactionInterface{

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        $codeGeneration = new CodeGeneration(ExpenseTransaction::class, "transaction_code", "EPS");
        $data['transaction_code'] = $codeGeneration->getGeneratedCode();

        ExpenseTransaction::create($data);

        return response()->json([
            "success" => true,
            "message" => "Transaksi pengeluaran berhasil ditambahkan!"
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function delete($id) {

        ExpenseTransaction::findOrFail($id)->delete();

        return response()->json([
            "success" => true,
            "message" => "Transaksi pengeluaran berhasil dihapus"
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function getAll() {
        $expenseTransactions = ExpenseTransaction::all();

        return response()->json([
            "success" => true,
            "data" => $expenseTransactions
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function getById($id) {
        $expenseTransaction = ExpenseTransaction::findOrFail($id);

        return response()->json([
            "success" => true,
            "data" => $expenseTransaction
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function update(array $data, $id) {
        ExpenseTransaction::findOrFail($id)->update($data);

        return response()->json([
            "success" => true,
            "message" => "Transaksi pengeluaran berhasil dirubah!"
        ], 200);
    }
}