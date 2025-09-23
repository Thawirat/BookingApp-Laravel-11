<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;

    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function build()
    {
        $fromName = '=?UTF-8?B?' . base64_encode('ระบบจองห้องประชุมมหาวิทยาลัยราชภัฏสกลนคร') . '?=';
        return $this->from('no-reply@snru.ac.th', $fromName)
            ->subject('แจ้งเตือน: รหัสยืนยันการรีเซ็ตรหัสผ่าน')
            ->markdown('otp-verify');
    }
}
