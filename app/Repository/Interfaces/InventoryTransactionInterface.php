<?php

namespace App\Repository\Interfaces;

interface InventoryTransactionInterface {
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}