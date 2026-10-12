<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmissionAdminMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public ContactSubmission $submission,
        public string $adminUrl,
    ) {}

    public function envelope(): Envelope
    {
        $envelope = new Envelope(
            subject: '[IK Saudi] Contact: '.$this->submission->reference_number,
        );

        if (filled($this->submission->email) && filter_var($this->submission->email, FILTER_VALIDATE_EMAIL)) {
            $envelope->replyTo = [
                new Address($this->submission->email, $this->submission->name),
            ];
        }

        return $envelope;
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.contact-submission-admin',
        );
    }
}
