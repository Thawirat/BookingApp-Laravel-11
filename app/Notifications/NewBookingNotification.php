<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Delivery channels
     */
    public function via($notifiable)
    {
        return ['database']; // บันทึกลง DB เพื่อแสดงในระบบ
    }

    /**
     * Database representation
     */
    public function toDatabase($notifiable)
    {
        return [
            'booking_id'  => $this->booking->id,
            'room'        => $this->booking->room_name,
            'building'    => $this->booking->building_name,
            'user_id'     => $this->booking->user_id,
            'type'        => 'new_booking',
            'message'     => "มีผู้ใช้ {$this->booking->user_name} จองห้อง {$this->booking->room_name} ในอาคาร {$this->booking->building_name}",
        ];
    }
}
