<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // <-- ต้องประกาศ public เพื่อให้ view เข้าถึงได้

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $fromName = '=?UTF-8?B?' . base64_encode('ระบบจองห้องประชุมมหาวิทยาลัยราชภัฏสกลนคร') . '?=';
        return $this->from('no-reply@snru.ac.th', $fromName)
            ->subject(subject: 'บัญชีของคุณได้รับการอนุมัติ')
            ->markdown('emails.users.approved');
    }

    public function attachments(): array
    {
        return [];
    }
}
