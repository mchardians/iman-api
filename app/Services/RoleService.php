<?php

namespace App\Services;

use App\Libraries\CodeGeneration;
use App\Models\Role;
use App\Repositories\Contracts\RoleContract;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleService
{
    private CodeGeneration $roleCode;
    public function __construct(protected RoleContract $roleRepository)
    {
        $this->roleCode = new CodeGeneration(Role::class, 'role_code', "ROL");
    }

    public function getAllRoles() {
        return $this->roleRepository->all();
    }

    public function getRoleById(string $id) {
        try {
            $role = $this->roleRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $role;
    }

    public function getRoleCode() {
        return $this->roleCode->getGeneratedResourceCode();
    }

    public function createRole(array $data) {
        return $this->roleRepository->create([
            "role_code" => $this->getRoleCode(),
            ...$data,
        ]);
    }
    public function updateRole(string $id, array $data) {
        $role = $this->getRoleById($id);

        try {
            return $this->roleRepository->update($id, $data) === true ? $role : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteRole(string $id) {
        $role = $this->getRoleById($id);

        try {
            return $this->roleRepository->delete($id) === true ? $role : false;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}