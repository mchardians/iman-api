<?php

namespace App\Repositories\Contracts;

interface NewsContract
{
    public function all();
    public function whereEquals(string $column, string $value);
    public function findOrFail(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}