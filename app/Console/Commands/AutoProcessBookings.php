<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\BookingHistory;
use Carbon\Carbon;

class AutoProcessBookings extends Command
{
    protected $signature = 'bookings:auto-process';
    protected $description = 'Update statuses and move past bookings to history';

    public function handle()
    {
        $now = Carbon::now();
        $cutoff = Carbon::today()->subDay(); // สิ้นสุดเมื่อวานหรือนานกว่านั้น
        $countMoved = 0;
        $countStatusUpdated = 0;

        // ✅ อัปเดตสถานะเป็น "กำลังดำเนินการ" (status_id = 7)
        $activeBookings = Booking::where('booking_start', '<=', $now)
            ->where('booking_end', '>', $now)
            ->whereNotIn('status_id', [5, 6, 7])
            ->get();

        foreach ($activeBookings as $booking) {
            $booking->status_id = 7;
            $booking->save();
            $countStatusUpdated++;
        }

        // ✅ อัปเดตสถานะการจองที่เลยเวลา
        Booking::where('booking_end', '<', $now)
            ->whereNotIn('status_id', [5, 6])
            ->chunk(100, function ($bookings) use (&$countStatusUpdated) {
                foreach ($bookings as $booking) {
                    if ($booking->status_id == 3) {
                        $booking->status_id = 5; // ถือว่ายกเลิก
                    } elseif ($booking->status_id == 4 || $booking->status_id == 7) {
                        $booking->status_id = 6; // ถือว่าเสร็จสิ้น
                    } else {
                        continue;
                    }

                    $booking->save();
                    $countStatusUpdated++;
                }
            });

        // ✅ ย้ายข้อมูลที่ควรเข้าประวัติ
        Booking::where('booking_end', '<=', $cutoff)
            ->whereIn('status_id', [5, 6])
            ->chunk(100, function ($bookings) use (&$countMoved) {
                foreach ($bookings as $booking) {
                    $alreadyMoved = BookingHistory::where('booking_id', $booking->id)->exists();
                    if ($alreadyMoved) continue;

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

                    $countMoved++;
                }
            });

        // 🟢 รายงานผล
        $this->info("Status updated: {$countStatusUpdated}");
        $this->info("Moved to history: {$countMoved}");
    }
}
