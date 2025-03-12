<?php

namespace App\Repository\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repository\Interfaces\UserInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService implements UserInterface {

    /**
     * @inheritDoc
     */
    public function create(array $data) {
        $data['user_code'] = $this->generateCode();

        $role = User::create($data);
        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => $role
        ], 201);
    }

    /**
     * @inheritDoc
     */
    public function delete($id) {
        $user = User::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
            'data' => $user
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
        $user = User::findOrFail($id)->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user
        ], 201);
    }

    public function generateCode() {
        try {
            // Mengambil kode terakhir
            $last_kode = User::select("user_code")
                ->whereMonth("created_at", Carbon::now())
                ->whereYear("created_at", Carbon::now())
                ->where(DB::raw("substr(user_code, 1, 3)"), "=", "USR")
                ->orderBy("user_code", "desc")
                ->first();

            $prefix = "USR";
            $year = date("y");
            $month = date("m");

            // Generate Kode
            if ($last_kode) {
                $monthKode = explode("/", $last_kode->user_code);
                $monthKode = substr($monthKode[1], 2, 4);
                if ($month == $monthKode) {
                    $last = explode("/", $last_kode->user_code);
                    $last[2] = (int)++$last[2];
                    $urutan = str_pad($last[2], 4, '0', STR_PAD_LEFT);
                    $kode = $prefix . "/" . $year . $month . "/" . $urutan;
                } else {
                    $kode = $prefix . "/" . $year . $month . "/" . "0001";
                }
            } else {
                $kode = $prefix . "/" . $year . $month . "/" . "0001";
            }

            return $kode;
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
