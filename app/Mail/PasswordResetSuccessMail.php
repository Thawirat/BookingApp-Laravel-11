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
        $fromName = '=?UTF-8?B?' . base64_encode('ระบบจองห้องประชุมมหาวิทยาลัยราชภัฏสกลนคร') . '?=';
        return $this->from('no-reply@snru.ac.th', $fromName)
                    ->subject('แจ้งเตือน: เปลี่ยนรหัสผ่านสำเร็จ')
                    ->view('emails.password-reset-success');
    }
}

