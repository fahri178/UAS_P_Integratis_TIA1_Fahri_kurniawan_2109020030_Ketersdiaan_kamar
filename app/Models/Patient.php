<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['name', 'admission_date', 'discharge_date', 'room_id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
