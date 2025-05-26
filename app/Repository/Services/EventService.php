<?php

namespace App\Repository\Services;

use App\Libraries\CodeGeneration;
use App\Models\Event;
use App\Repository\Interfaces\EventInterface;

class EventService implements EventInterface
{

    public function getAll()
    {
        $events = Event::all();
        return response()->json([
            'success' => true,
            'data' => $events
        ], 200);
    }

    public function getById($id) {
        $event = Event::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $event
        ], 200);
    }

    public function create(array $data) {
        $codeGeneration = new CodeGeneration(Event::class, "event_code", "EVT");

        $data['event_code'] = $codeGeneration->getGeneratedResourceCode();
        Event::create($data);

        return response()->json([
            'success' => true,
            'data' => 'Event berhasil ditambahkan'
        ], 201);
    }

    public function update(array $data, $id) {
        $event = Event::findOrFail($id);
        $event->update($data);
        return response()->json([
            'success' => true,
            'data' => $event
        ], 200);
    }

    public function delete($id) {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json([
            'success' => true,
            'data' => $event
        ], 200);

        }
    }
