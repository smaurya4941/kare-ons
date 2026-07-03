<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReply extends Mailable
{
    use Queueable, SerializesModels;

    public ContactInquiry $inquiry;
    public string $replyMessage;
    public string $replySubject;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactInquiry $inquiry, string $replySubject, string $replyMessage)
    {
        $this->inquiry = $inquiry;
        $this->replySubject = $replySubject;
        $this->replyMessage = $replyMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replySubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.inquiries.reply',
        );
    }
}
