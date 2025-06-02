<?php

namespace App\Repositories\Contracts;

interface FinanceIncomeContract
{
    public function all();
    public function findOrFail(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}