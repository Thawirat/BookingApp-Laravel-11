<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // ให้ view เข้าถึงข้อมูลผู้ใช้

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'บัญชีของคุณไม่ได้รับการอนุมัติ',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.users.rejected',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
