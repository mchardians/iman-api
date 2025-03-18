<?php 

namespace App\Repository\Services;

use Carbon\Carbon;
use App\Models\InfaqType;
use App\Helpers\CodeGeneration;
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
        $codeGeneration = new CodeGeneration(InfaqType::class, "infaq_type_code", "INQ");
        $data['infaq_type_code'] = $codeGeneration->getGeneratedCode();

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
    
}

