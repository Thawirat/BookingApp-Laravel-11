<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('แจ้งเตือน: คุณได้เปลี่ยนรหัสผ่านสำเร็จแล้ว')
                    ->view('emails.password-reset-success');
    }
}

