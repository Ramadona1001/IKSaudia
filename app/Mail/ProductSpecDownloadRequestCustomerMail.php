<?php

namespace App\Mail;

use App\Models\ProductSpecDownloadRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductSpecDownloadRequestCustomerMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public ProductSpecDownloadRequest $downloadRequest,
        public string $productTitle,
        public string $locale,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->locale === 'ar'
            ? '[IK Saudi] تم استلام طلب المواصفات — '.$this->downloadRequest->reference_number
            : '[IK Saudi] We received your PDF request — '.$this->downloadRequest->reference_number;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.product-spec-request-customer',
            with: ['locale' => $this->locale],
        );
    }
}
