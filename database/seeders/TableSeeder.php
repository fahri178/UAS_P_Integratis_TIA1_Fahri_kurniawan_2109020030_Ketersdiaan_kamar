<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\RoomLevel;
use App\Models\Room;
use App\Models\Patient;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed Room Levels
        $roomLevels = [
            ['level_name' => 'VIP', 'total_rooms' => 10, 'available_rooms' => 10],
            ['level_name' => 'Kelas 1', 'total_rooms' => 20, 'available_rooms' => 20],
            ['level_name' => 'Kelas 2', 'total_rooms' => 30, 'available_rooms' => 30],
        ];

        foreach ($roomLevels as $level) {
            RoomLevel::create($level);
        }

        // Seed Rooms
        $rooms = [];
        $roomNumber = 1;

        foreach ($roomLevels as $index => $level) {
            for ($i = 0; $i < $level['total_rooms']; $i++) {
                $rooms[] = [
                    'room_number' => 'Room ' . $roomNumber++,
                    'level' => $level['level_name'],
                    'status' => 'available',
                    'room_level_id' => $index + 1,
                ];
            }
        }

        foreach ($rooms as $room) {
            Room::create($room);
        }

        // Seed Patients
        $patients = [
            ['name' => 'John Doe', 'admission_date' => now()->subDays(2), 'room_id' => 1],
            ['name' => 'Jane Smith', 'admission_date' => now()->subDay(), 'room_id' => 2],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);

            // Update room status and available rooms
            $room = Room::find($patient['room_id']);
            $room->update(['status' => 'occupied']);
            $room->roomLevel->decrement('available_rooms');
        }
    }
}
