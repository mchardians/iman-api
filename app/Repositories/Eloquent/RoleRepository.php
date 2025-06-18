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

    public function baseQuery() {
        return $this->role->select('id', 'role_code', 'name', 'created_at');
    }

    public function all(array $filters = []) {
        return $this->baseQuery()->where($filters)->latest()->get();
    }

    public function paginate(?string $perPage = null, array $filters = []) {
        return $this->baseQuery()->where($filters)->latest()->paginate($perPage);
    }

    public function findOrFail(string $id) {
        return $this->baseQuery()->findOrFail($id);
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