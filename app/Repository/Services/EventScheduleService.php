<?php

namespace App\Repository\Services;

use App\Models\EventSchedule;
use App\Repository\Interfaces\EventScheduleInterface;
use App\Helpers\CodeGeneration;

class EventScheduleService implements EventScheduleInterface
{
    public function getAll()
    {
        $eventSchedule = EventSchedule::all();
        return response()->json([
            'success' => true,
            'data' => $eventSchedule
        ], 200);

    }

    public function getById($id) {
        $eventSchedule = EventSchedule::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $eventSchedule
        ], 200);
    }

    public function create(array $data)
    {
        $codeGeneration = new CodeGeneration(EventSchedule::class, "event_schedule_code", "EVS");
        $data['event_schedule_code'] = $codeGeneration->getGeneratedCode();
        $eventSchedule = EventSchedule::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Jadwal Event berhasil ditambahkan',
            'data' => $eventSchedule
        ], 201);
    }

    public function update(array $data, $id) {
        $eventSchedule = EventSchedule::findOrFail($id);
        $eventSchedule->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Jadwal Event berhasil diedit',
            'data' => $eventSchedule
        ], 201);
    }

    public function delete($id) {
        $eventSchedule = EventSchedule::findOrFail($id);
        $eventSchedule->delete();
        return response()->json([
            'success' => true,
            'message' => 'Jadwal Event berhasil dihapus',
            'data' => $eventSchedule
        ], 201);
    }
}
