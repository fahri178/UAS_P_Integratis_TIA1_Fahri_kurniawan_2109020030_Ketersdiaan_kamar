<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Room;
use App\Models\RoomLevel;

class PatientController extends Controller
{
    public function list()
    {
        $patients = Patient::all();
        return response()->json([
            'data' => $patients
        ]);
    }

    public function admit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_id' => 'required|integer|exists:rooms,id',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if ($room->status !== 'available') {
            return response()->json(['message' => 'Kamar yang dipilih Tidak Tesedia atau Sudah ditempati oleh Pasien lain'], 200);
        }

        $patient = Patient::create([
            'name' => $validated['name'],
            'admission_date' => now(),
            'room_id' => $validated['room_id'],
        ]);
        // Pesan untuk dicatat atau dikirim sebagai respons
        $message = "Kamar yang dipilih adalah {$room->room_number} Tipe {$room->level}";
        $room->update(['status' => 'occupied']);
        $room->roomLevel->decrement('available_rooms');

        return response()->json([
            'message' => $message,

            'patient Atas' => $patient
        ]);
    }

    public function discharge($id)
    {
        $patient = Patient::findOrFail($id);

        $patient->update(['discharge_date' => now()]);

        $room = Room::findOrFail($patient->room_id);

        $roomNumber = $room->room_number; // Ambil nomor kamar dari table room
        $roomType = $room->level; // Ambil jenis kamar (contoh: "Single", "Double", dll)

        // Mengubah status kamar menjadi tersedia
        $room->update(['status' => 'available']);

        // Menambah jumlah kamar yang tersedia di tingkat kamar (roomLevel)
        $room->roomLevel->increment('available_rooms');

        // Pesan untuk dicatat atau dikirim sebagai respons
        $message = "Pasien Telah Keluar {$roomNumber}, Tipe {$roomType} tersedia kembali";

        // Return response JSON dengan pesan tambahan
        return response()->json([
            'patient' => $patient,
            'message' => $message
        ]);
    }



    public function availableRooms()
    {
        $rooms = Room::where('status', 'available')->get();
        $message = "Data Ruangan yang masih Tersedia";
        return response()->json([
            'message' => $message,
            'data' => $rooms
        ]);
    }

    public function occupiedRooms()
    {
        $rooms = Room::where('status', 'occupied')->get();
        $message = "Data Ruangan yang sudah digunakan";
        return response()->json([
            'message' => $message,
            'data' => $rooms
        ]);
    }
}
