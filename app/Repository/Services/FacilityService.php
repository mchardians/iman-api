<?php

namespace App\Services;

use App\Models\Facility;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FacilityService
{
    public function update(array $data, string $id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return response()->json(['message' => 'Facility not found'], 404);
        }

        $facility->update($data);

        return response()->json(['message' => 'Facility updated successfully', 'data' => $facility], 200);
    }
}
