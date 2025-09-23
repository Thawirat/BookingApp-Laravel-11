<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRejectedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public function __construct($booking)
    {
        $this->booking = $booking;
    }
    public function build()
    {
        return $this->subject('แจ้งผลการจองห้องประชุมมหาวิทยาลัยราชภัฏสกลนคร')
            ->view('emails.bookings.booking_rejected');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
