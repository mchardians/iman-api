<?php

namespace App\Repository\Services;

use App\Libraries\CodeGeneration;
use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Repository\Interfaces\InventoryTransactionInterface;
use Illuminate\Support\Facades\DB;

class InventoryTransactionService implements InventoryTransactionInterface {

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        return DB::transaction(function () use ($data) {
            $codeGeneration = new CodeGeneration(InventoryTransaction::class, "inventory_transaction_code", "ITC");
            $data["inventory_transaction_code"] = $codeGeneration->getGeneratedCode();

            $newInventoryTransaction = InventoryTransaction::create($data);

            $itemTarget = Item::findOrFail($newInventoryTransaction->item_id);
            $itemTarget->increment('quantity', $newInventoryTransaction->quantity);

            return response()->json([
                "success" => true,
                "message" => "Transaksi barang berhasil ditambahkan!"
            ], 201);
        });
    }

    /**
     * @inheritDoc
     */
    public function delete($id) {

        return DB::transaction(function () use ($id) {
            $inventoryTransactionTarget = InventoryTransaction::findOrFail($id);
            $itemTarget = Item::findOrFail($inventoryTransactionTarget->item_id);

            $itemTarget->decrement('quantity', $inventoryTransactionTarget->quantity);

            $inventoryTransactionTarget->delete();

            return response()->json([
                "success" => true,
                "message" => "Transaksi barang berhasil dihapus!"
            ]);
        });
    }

    /**
     * @inheritDoc
     */
    public function getAll() {
        $inventoryTransactions = InventoryTransaction::all();

        return response()->json([
            "success" => true,
            "data" => $inventoryTransactions
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function getById($id) {
        $inventoryTransaction = InventoryTransaction::findOrFail($id);

        return response()->json([
            "success" => true,
            "data" => $inventoryTransaction
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function update(array $data, $id) {

        return DB::transaction(function () use ($data, $id) {
            $inventoryTransactiontarget = InventoryTransaction::findOrFail($id);
            $oldQuantity = $inventoryTransactiontarget->quantity;

            $inventoryTransactiontarget->update($data);

            $itemTarget = Item::findOrFail($data['item_id']);
            $itemTarget->update([
                'quantity' => $itemTarget->quantity - $oldQuantity + $data['quantity'],
            ]);

            return response()->json([
                "success" => true,
                "message" => "Transaksi barang berhasil diperbarui!"
            ]);
        });
    }
}