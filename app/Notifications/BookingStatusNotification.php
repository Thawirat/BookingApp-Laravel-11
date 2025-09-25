<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    protected $booking;
    protected $status;

    public function __construct($booking, $status)
    {
        $this->booking = $booking;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'booking_id'  => $this->booking->id,
            'room'        => $this->booking->room_name,
            'building'    => $this->booking->building_name,
            'status'      => $this->status->status_name,
            'user_id'     => $this->booking->user_id,   // ใครเป็นเจ้าของการจอง
            'building_id' => $this->booking->building_id, // เอาไว้ให้ SubAdmin ใช้กรอง
            'type'        => 'booking_status', // เผื่อแยกประเภทแจ้งเตือน
            'message'     => "การจองห้อง {$this->booking->room_name} ถูกเปลี่ยนสถานะเป็น {$this->status->status_name}",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
