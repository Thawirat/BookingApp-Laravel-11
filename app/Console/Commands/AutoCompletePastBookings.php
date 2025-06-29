<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Http\Controllers\BookingHistoryController;
use Carbon\Carbon;

class AutoCompletePastBookings extends Command
{
    protected $signature = 'booking:auto-complete';
    protected $description = 'Update bookings that are past and move them to history';

    public function handle()
    {
        $now = Carbon::now();

        $pastBookings = Booking::where('booking_end', '<', $now)
            ->whereNotIn('status_id', [5, 6])
            ->get();

        foreach ($pastBookings as $booking) {
            if ($booking->status_id == 3) {
                $booking->status_id = 5;
            } elseif ($booking->status_id == 4) {
                $booking->status_id = 6;
            } else {
                continue;
            }

            $booking->save();

            $historyController = new BookingHistoryController;
            $historyController->addBookingToHistory($booking);
        }

        $this->info("Auto-complete bookings finished.");
    }
}
