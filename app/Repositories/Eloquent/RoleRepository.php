<?php

namespace App\Repositories\Eloquent;
use App\Models\Role;
use App\Repositories\Contracts\RoleContract;

class RoleRepository Implements RoleContract
{
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function all() {
        return $this->role->select('id', 'role_code', 'name', 'created_at')->get();
    }

    public function findOrFail(string $id) {
        return $this->role->select('id', 'role_code', 'name', 'created_at')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->role->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->role->findOrFail($id)->updateOrFail($data);
    }

    public function delete(string $id)
    {
        return $this->role->findOrFail($id)->deleteOrFail();
    }
}