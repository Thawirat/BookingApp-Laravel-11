<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomEquipment extends Model
{
     use HasFactory;

    protected $table = 'room_equipments'; // กำหนดชื่อตารางให้แน่นอน
    protected $fillable = ['building_id', 'room_id', 'name', 'quantity', 'note'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
