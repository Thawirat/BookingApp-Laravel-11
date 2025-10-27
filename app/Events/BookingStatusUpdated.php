<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BookingStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $totalBookings;
    public $pendingBookings;
    public $confirmedBookings;

    public function __construct($total, $pending, $confirmed)
    {
        $this->totalBookings = $total;
        $this->pendingBookings = $pending;
        $this->confirmedBookings = $confirmed;
    }

    public function broadcastOn()
    {
        return new Channel('booking-channel');
    }

    public function broadcastAs()
    {
        return 'BookingUpdated';
    }
}
