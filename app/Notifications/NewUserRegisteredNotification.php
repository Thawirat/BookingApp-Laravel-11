<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * ช่องทางการแจ้งเตือน
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * เก็บข้อมูลลง Database
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'name'    => $this->user->name,
            'email'   => $this->user->email,
            'type'    => 'new_user',
            'message' => "มีผู้ใช้ใหม่สมัครสมาชิก: {$this->user->name} ({$this->user->email})",
        ];
    }
}
