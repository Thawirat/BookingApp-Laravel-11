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
        $cutoff = Carbon::today()->subDay(); // เวลาสิ้นสุดต้องจบ "เมื่อวาน" หรือนานกว่านั้น
        $count = 0;

        Booking::where('booking_end', '<=', $cutoff)
            ->whereIn('status_id', [3, 4, 5, 6]) // จองยังไม่อนุมัติ, อนุมัติแล้ว, ยกเลิก, เสร็จสิ้น
            ->chunk(100, function ($bookings) use (&$count) {
                foreach ($bookings as $booking) {
                    $alreadyMoved = BookingHistory::where('booking_id', $booking->id)->exists();
                    if ($alreadyMoved) {
                        continue;
                    }

                    BookingHistory::create([
                        'booking_id'         => $booking->id,
                        'user_id'            => $booking->user_id,
                        'ref_number'         => $booking->booking_id,
                        'external_name'      => $booking->external_name,
                        'external_email'     => $booking->external_email,
                        'external_phone'     => $booking->external_phone,
                        'building_id'        => $booking->building_id,
                        'building_name'      => optional($booking->building)->name,
                        'room_id'            => $booking->room_id,
                        'room_name'          => optional($booking->room)->name,
                        'booking_date'       => $booking->created_at,
                        'booking_start'      => $booking->booking_start,
                        'booking_end'        => $booking->booking_end,
                        'status_id'          => $booking->status_id,
                        'status_name'        => optional($booking->status)->name,
                        'reason'             => $booking->reason,
                        'participant_count' => $booking->participant_count,
                        'booker_info' => $booking->booker_info,
                        'approver_name' => $booking->approver_name,
                        'approver_position' => $booking->approver_position,
                        'total_price'        => $booking->amount,
                        'payment_status'     => $booking->payment_status,
                        'is_external'        => $booking->is_external,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                        'moved_to_history_at' => now(),
                        'title' => $booking->title ?? null,
                        'setup_date' => $booking->setup_date ?? null,
                        'teardown_date' => $booking->teardown_date ?? null,
                        'additional_equipment' => $booking->additional_equipment ?? null,
                        'coordinator_name' => $booking->coordinator_name ?? null,
                        'coordinator_phone' => $booking->coordinator_phone ?? null,
                        'coordinator_department' => $booking->coordinator_department ?? null,
                    ]);

                    if (!in_array($booking->status_id, [5, 6])) {
                        if ($booking->status_id == 3) {
                            // ยังไม่อนุมัติ → ถือว่า "ยกเลิก"
                            $booking->status_id = 5;
                        } else {
                            // ได้รับอนุมัติหรือกำลังใช้งาน → ถือว่า "เสร็จสิ้น"
                            $booking->status_id = 6;
                        }
                        $booking->save();
                    }
                    $count++;
                }
            });

        $this->info("Moved {$count} bookings to history.");
    }
}
