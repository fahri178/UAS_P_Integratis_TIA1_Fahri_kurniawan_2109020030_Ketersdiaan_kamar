<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_number', 'level', 'status', 'room_level_id'];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function roomLevel()
    {
        return $this->belongsTo(RoomLevel::class);
    }
}
