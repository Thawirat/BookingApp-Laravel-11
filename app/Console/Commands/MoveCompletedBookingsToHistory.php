<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BookingHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MoveCompletedBookingsToHistory extends Command
{
    protected $signature = 'bookings:move-to-history';

    protected $description = 'Move completed bookings to history table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();
        $count = 0;

        Booking::where(function ($query) use ($today) {
            $query->where('booking_start', '<', $today)
                ->orWhereIn('status_id', [5, 6]);
        })->chunk(100, function ($bookings) use (&$count) {
            foreach ($bookings as $booking) {
                $alreadyMoved = \App\Models\BookingHistory::where('booking_id', $booking->id)->exists();
                if ($alreadyMoved) {
                    continue;
                }

                \App\Models\BookingHistory::create([
                    'booking_id'         => $booking->id,
                    'user_id'            => $booking->user_id,
                    'external_name'      => $booking->external_name,
                    'external_email'     => $booking->external_email,
                    'external_phone'     => $booking->external_phone,
                    'building_id'        => $booking->building_id,
                    'building_name'      => optional($booking->building)->name,
                    'room_id'            => $booking->room_id,
                    'room_name'          => optional($booking->room)->name,
                    'booking_start'      => $booking->booking_start,
                    'booking_end'        => $booking->booking_end,
                    'status_id'          => $booking->status_id,
                    'status_name'        => optional($booking->status)->name,
                    'reason'             => $booking->reason,
                    'total_price'        => $booking->amount,
                    'payment_status'     => $booking->payment_status,
                    'is_external'        => $booking->is_external,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                    'moved_to_history_at' => now(), // ✅ เพิ่มตรงนี้
                ]);

                if (!in_array($booking->status_id, [5, 6])) {
                    $booking->status_id = 6; // เสร็จสิ้น
                    $booking->save();
                }

                $count++;
            }
        });

        $this->info("Moved {$count} bookings to history.");
    }
}
