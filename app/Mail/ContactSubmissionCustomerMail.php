<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmissionCustomerMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public ContactSubmission $submission,
        string $locale,
    ) {
        $this->locale = $locale;
    }

    public function envelope(): Envelope
    {
        $subject = $this->locale === 'ar'
            ? '[IK Saudi] تم استلام رسالتك — '.$this->submission->reference_number
            : '[IK Saudi] We received your message — '.$this->submission->reference_number;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.contact-submission-customer',
        );
    }
}
