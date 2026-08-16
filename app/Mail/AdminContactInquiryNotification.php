<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminContactInquiryNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ContactInquiry $inquiry;

    public function __construct(ContactInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Form Submission' . ($this->inquiry->subject ? ' — ' . $this->inquiry->subject : ''),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.contact_inquiry',
        );
    }
}
