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
        return $this->role->select('name','created_at')->get();
    }

    public function findOrFail(string $id) {
        return $this->role->select('name', 'created_at')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->role->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->role->findOrFail($id)->update($data);
    }

    public function delete(string $id)
    {
        return $this->role->findOrFail($id)->deleteOrFail();
    }

    public function getPaginatedRoles(array $filter, int $itemPerPage = 0, string $sort = '') {
        $query = $this->role->select('name', 'created_at');

        if (isset($filter['nama'])) {
            $query->where('name', 'like', "%{$filter['search']}%");
        }

        $sort = $sort ?? 'id DESC';
        $query->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $query->paginate($itemPerPage)->appends('sort', $sort);
    }

//    public function getPaginatedRole(array $filter, int $itemPerPage = 5, string $sort = '')
//    {
//        $query = $this->role->select('name', 'created_at');
//
//        if (isset($filter['nama'])) {
//            $query->where('name', 'like', "%{$filter['search']}%");
//        }
//
//        $sort = $sort ?? 'id DESC';
//        $query->orderByRaw($sort);
//        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;
//
//        return $query->paginate($itemPerPage)->appends('sort', $sort);
//    }
}