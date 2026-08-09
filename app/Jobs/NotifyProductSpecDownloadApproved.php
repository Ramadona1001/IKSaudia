<?php

namespace App\Jobs;

use App\Models\ProductSpecDownloadRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyProductSpecDownloadApproved implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public ProductSpecDownloadRequest $request,
    ) {}

    public function handle(): void
    {
        $this->request->loadMissing(['product.translations']);

        if (! $this->request->tokenIsValid()) {
            return;
        }

        $productTitle = $this->request->product?->translate($this->request->locale)?->title ?? 'Product';
        $downloadUrl = route('products.spec-download', ['token' => $this->request->download_token]);

        if (config('mail.default') === 'log') {
            Log::info('Product spec download approved', [
                'reference' => $this->request->reference_number,
                'download_url' => $downloadUrl,
            ]);

            return;
        }

        $lines = [
            'Your product specification download has been approved.',
            '',
            'Reference: '.$this->request->reference_number,
            'Product: '.$productTitle,
            '',
            'Download your PDF (link valid for 7 days):',
            $downloadUrl,
        ];

        Mail::raw(
            implode("\n", $lines),
            fn ($message) => $message
                ->to($this->request->email, $this->request->name)
                ->subject('[IK Saudi] Your PDF download is ready — '.$this->request->reference_number),
        );
    }
}
