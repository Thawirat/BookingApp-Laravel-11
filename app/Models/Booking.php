<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime',
        'booking_end' => 'datetime',
        'booking_start' => 'datetime',
        'verified_at' => 'datetime',
    ];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_id');
    }
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    protected $fillable = [
        'booking_id',
        'user_id',
        'external_name',
        'external_email',
        'external_phone',
        'external_position',
        'external_address',
        'building_id',
        'room_id',
        'room_name',
        'building_name',
        'booking_start',
        'booking_end',
        'status_id',
        'reason',
        'approver_name',
        'total_price',
        'payment_status',
        'is_external',
        'status',
        'payment_slip',
        'participant_count',
        'booker_info',
        'delete_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            // สร้าง booking_id ถ้ายังไม่มี
            if (empty($booking->booking_id)) {
                $latestId = Booking::max('id') + 1;
                $booking->booking_id = str_pad($latestId, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
