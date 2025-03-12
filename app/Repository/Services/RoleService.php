<?php 

namespace App\Repository\Services;

use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Repository\Interfaces\RoleInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $data['role_code'] = $this->generateKode();

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

    public static function generateKode()
    {
        try {
            // Mengambil kode terakhir
            $last_kode = Role::select("role_code")
                ->whereMonth("created_at", Carbon::now())
                ->whereYear("created_at", Carbon::now())
                ->where(DB::raw("substr(role_code, 1, 3)"), "=", "ROL")
                ->orderBy("role_code", "desc")
                ->first();

            $prefix = "ROL";
            $year = date("y");
            $month = date("m");

            // Generate Kode
            if ($last_kode) {
                $monthKode = explode("/", $last_kode->role_code);
                $monthKode = substr($monthKode[1], 2, 4);
                if ($month == $monthKode) {
                    $last = explode("/", $last_kode->role_code);
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