<?php

namespace App\Http\Controllers\Api;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    // Menampilkan semua fasilitas
    public function index()
    {
        return response()->json(Facility::all(), 200);
    }

    // Menyimpan fasilitas baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:facilities',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable'
        ]);

        if($validator->fails()) {
            return response()->json([
                "success" => false,
                "message"=> $validator->errors(),
            ],422);
        }

        $facility = Facility::create([
            'name' => $request->name,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Facility created successfully', 'data' => $facility], 201);
    }

    // Menampilkan satu fasilitas berdasarkan ID
    public function show($id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return response()->json(['message' => 'Facility not found'], 404);
        }

        return response()->json($facility, 200);
    }

    // Mengupdate fasilitas
    public function update(Request $request, $id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return response()->json(['message' => 'Facility not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:facilities,name,' . $id,
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable'
        ]);

        if($validator->fails()) {
            return response()->json([
                "success" => false,
                "message"=> $validator->errors(),
            ],422);
        }

        $facility->update([
            'name' => $request->name,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Facility updated successfully', 'data' => $facility], 200);
    }

    // Menghapus fasilitas
    public function destroy($id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return response()->json(['message' => 'Facility not found'], 404);
        }

        $facility->delete();

        return response()->json(['message' => 'Facility deleted successfully'], 200);
    }
}