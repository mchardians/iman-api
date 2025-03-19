<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\IncomeInfaqTransaction;

class InfaqMasukController extends Controller
{
    // Menampilkan semua data infaq masuk
    public function index()
{
    $infaq = IncomeInfaqTransaction::with('infaqType')->get();
    return response()->json($infaq);
}


    // Menyimpan data infaq masuk baru
    public function store(Request $request)
    {
        $request->validate([
            'infaq_type_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
        ]);

        $infaq = IncomeInfaqTransaction::create([
            'infaq_type_id' => $request->infaq_type_id,
            'transaction_code' => Str::uuid(), // Generate kode unik
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        return response()->json([
            'message' => 'Data infaq masuk berhasil ditambahkan',
            'data' => $infaq
        ], 201);
    }

    // Menampilkan detail data infaq masuk
    public function show($id)
    {
        $infaq = IncomeInfaqTransaction::with('infaqType')->find($id);

        if (!$infaq) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($infaq, 200);
    }

    // Memperbarui data infaq masuk
    public function update(Request $request, $id)
{
    // Validasi input dari request
    $validator = Validator::make($request->all(), [
        'infaq_type_id' => 'required|exists:infaq_types,id',
        'name' => 'required|string|max:255',
        'amount' => 'required|integer|min:1'
    ]);

    if($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Cari data berdasarkan ID
    $infaq = IncomeInfaqTransaction::find($id);

    // Jika data tidak ditemukan, kembalikan response 404
    if (!$infaq) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    // Update data
    $infaq->update($validator->validated());

    return response()->json([
        'message' => 'Data berhasil diperbarui',
        'data' => $infaq
    ], 200);
}


    // Menghapus data infaq masuk
    public function destroy($id)
    {
        $infaq = IncomeInfaqTransaction::find($id);

        if (!$infaq) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $infaq->delete();

        return response()->json(['message' => 'Data infaq masuk berhasil dihapus'], 200);
    }
}