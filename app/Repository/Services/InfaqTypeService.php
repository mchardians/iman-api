<?php 

namespace App\Repository\Services;

use App\Models\InfaqType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Repository\Interfaces\InfaqTypeInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InfaqTypeService implements InfaqTypeInterface {
    public function getAll(){
        $infaqTypes = InfaqType::all();
        return response()->json([
            'success' => true,
            'data' => $infaqTypes
        ], 200);
    }

    public function getById($id){
        $infaqType = InfaqType::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $infaqType
        ], 200);
    }

    public function create(array $data) {
        $data['infaq_type_code'] = $this->generateKode();

        $infaqType = InfaqType::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Jenis Infaq berhasil ditambahkan',
            'data' => $infaqType
        ], 201);
    }

    public function update(array $data, $id) {
        $infaqType = InfaqType::findOrFail($id)->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Jenis Infaq berhasil diedit',
            'data' => $infaqType
        ], 201);
    }

    public function delete($id) {
        $infaqType = InfaqType::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Jenis Infaq berhasil dihapus',
            'data' => $infaqType
        ], 201);
    }
    public static function generateKode()
    {
        try {
            // Mengambil kode terakhir
            $last_kode = InfaqType::select("infaq_type_code")
                ->whereMonth("created_at", Carbon::now())
                ->whereYear("created_at", Carbon::now())
                ->where(DB::raw("substr(infaq_type_code, 1, 3)"), "=", "INQ")
                ->orderBy("infaq_type_code", "desc")
                ->first();

            $prefix = "INQ";
            $year = date("y");
            $month = date("m");

            // Generate Kode
            if ($last_kode) {
                $monthKode = explode("/", $last_kode->infaq_type_code);
                $monthKode = substr($monthKode[1], 2, 4);
                if ($month == $monthKode) {
                    $last = explode("/", $last_kode->infaq_type_code);
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

