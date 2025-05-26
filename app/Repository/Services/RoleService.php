<?php

namespace App\Repository\Services;

use App\Libraries\CodeGeneration;
use App\Models\Role;
use App\Repository\Interfaces\RoleInterface;

class RoleService implements RoleInterface
{
    public function getAll()
    {
      $roles = Role::all();
      return response()->json([
        'success' => true,
        'data' => $roles
      ], 200);
    }

    public function getById($id)
    {
      $role = Role::findOrFail($id);
      return response()->json([
        'success' => true,
        'data' => $role
      ], 200);
    }

    public function create(array $data)
    {
        $codeGeneration = new CodeGeneration(Role::class, "role_code", "ROL");

        $data['role_code'] = $codeGeneration->getGeneratedResourceCode();
        $role = Role::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil ditambahkan',
            'data' => $role
        ], 201);
    }

    public function update(array $data, $id)
    {
      $role = Role::findOrFail($id)->update($data);
      return response()->json([
          'success' => true,
          'message' => 'Role berhasil diedit',
          'data' => $role
      ], 201);
    }

    public function delete($id)
    {
      $role = Role::findOrFail($id)->delete();
      return response()->json([
          'success' => true,
          'message' => 'Role berhasil dihapus',
          'data' => $role
      ], 201);
    }
}