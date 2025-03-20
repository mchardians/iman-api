<?php 

namespace App\Repository\Services;

use App\Helpers\CodeGeneration;
use App\Models\FacilityReservation;
use App\Repository\Interfaces\FacilityReservationInterface;

class FacilityReservationService implements FacilityReservationInterface {
    public function getAll() {
        $facilityReservations = FacilityReservation::with('facility', 'user')->get();
        return response()->json([
            'status' => 'success',
            'data' => $facilityReservations
        ], 200);
    }

    public function getById($id) {
        $facilityReservation = FacilityReservation::with('facility', 'user')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $facilityReservation
        ], 200);
    }

    public function create(array $data) {
        $codeGeneration = new CodeGeneration(FacilityReservation::class, "facility_reservation_code", "FCR");
        $data['facility_reservation_code'] = $codeGeneration->getGeneratedCode();

        $facilityReservation = FacilityReservation::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Reservasi Fasilitas berhasil ditambahkan',
            'data' => $facilityReservation
        ], 201);
    }

    public function update(array $data, $id) {
        $facilityReservation = FacilityReservation::findOrFail($id);
        $facilityReservation->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Reservasi Fasilitas berhasil diubah',
            'data' => $facilityReservation
        ], 200);
    }

    public function delete($id) {
        $facilityReservation = FacilityReservation::findOrFail($id);
        $facilityReservation->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Reservasi Fasilitas berhasil dihapus'
        ], 200);
    }
}