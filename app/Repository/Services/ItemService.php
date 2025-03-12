<?php

namespace App\Repository\Services;

use App\Models\Item;
use App\Repository\Interfaces\ItemInterface;

class ItemService implements ItemInterface
{
    public function getAll()
    {
        $items = Item::all();
        return response()->json([
            'success' => true,
            'data' => $items
        ], 200);
    }

    public function getById($id)
    {
        $item = Item::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $item
        ], 200);
    }

    public function create(array $data)
    {
        $item = Item::create($data);
        return response()->json([
            'success' => true,
            'data' => $item
        ], 201);
    }

    public function update(array $data, $id)
    {
        $item = Item::findOrFail($id);
        $item->update($data);
        return response()->json([
            'success' => true,
            'data' => $item
        ], 200);
    }

    public function delete($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json([
            'success' => true,
            'data' => $item
        ], 200);
    }
}