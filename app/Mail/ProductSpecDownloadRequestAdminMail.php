<?php

namespace App\Mail;

use App\Models\ProductSpecDownloadRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductSpecDownloadRequestAdminMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public ProductSpecDownloadRequest $downloadRequest,
        public string $productTitle,
        public string $adminUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[IK Saudi] PDF request: '.$this->downloadRequest->reference_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.product-spec-request-admin',
        );
    }
}
