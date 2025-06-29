<?php

namespace App\Repositories\Contracts;

interface FinanceExpenseContract
{
    public function baseQuery();
    public function all(array $filters = []);
    public function paginate(?string $perPage = null, array $filters = []);
    public function findOrFail(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}