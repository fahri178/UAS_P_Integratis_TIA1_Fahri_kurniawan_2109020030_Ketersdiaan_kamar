<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomLevel extends Model
{
    protected $fillable = ['level_name', 'total_rooms', 'available_rooms'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
