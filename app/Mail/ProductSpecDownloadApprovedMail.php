<?php

namespace App\Mail;

use App\Models\ProductSpecDownloadRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductSpecDownloadApprovedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $mailLocale;

    public function __construct(
        public ProductSpecDownloadRequest $downloadRequest,
        public string $productTitle,
        public string $downloadUrl,
        string $locale,
    ) {
        $this->mailLocale = $locale;
        $this->viewData['locale'] = $locale;
    }

    public function envelope(): Envelope
    {
        $subject = $this->mailLocale === 'ar'
            ? '[IK Saudi] رابط تحميل المواصفات جاهز — '.$this->downloadRequest->reference_number
            : '[IK Saudi] Your PDF download is ready — '.$this->downloadRequest->reference_number;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.product-spec-approved',
        );
    }
}
