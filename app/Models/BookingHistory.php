<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    use HasFactory;

    protected $table = 'booking_history';

    protected $fillable = [
    'booking_id',
    'ref_number',
    'user_id',
    'external_name',
    'external_email',
    'external_phone',
    'building_id',
    'building_name',
    'room_id',
    'room_name',
    'booking_start',
    'booking_end',
    'status_id',
    'status_name',
    'reason',
    'total_price',
    'payment_status',
    'is_external',
    'created_at',
    'updated_at',
    'moved_to_history_at',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }

     public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

}
