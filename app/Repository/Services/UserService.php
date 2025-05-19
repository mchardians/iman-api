<?php

namespace App\Repository\Services;

use App\Libraries\CodeGeneration;
use App\Models\User;
use App\Repository\Interfaces\UserInterface;

class UserService implements UserInterface {

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        $codeGeneration = new CodeGeneration(User::class, "user_code", "USR");

        $data['user_code'] = $codeGeneration->getGeneratedCode();
        User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
        ], 201);
    }

    /**
     * @inheritDoc
     */
    public function delete($id) {
        User::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ], 201);
    }

    /**
     * @inheritDoc
     */
    public function getAll() {
        $users = User::all();

        return response()->json([
            "success" => false,
            "data" => $users
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function getById($id) {
        $user = User::findOrFail($id)->first();

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    /**
     * @inheritDoc
     */
    public function update(array $data, $id) {
        User::findOrFail($id)->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
        ], 201);
    }
}
