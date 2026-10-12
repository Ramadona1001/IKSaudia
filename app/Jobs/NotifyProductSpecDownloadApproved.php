<?php

namespace App\Jobs;

use App\Mail\ProductSpecDownloadApprovedMail;
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

        if (! $this->request->tokenIsValid() || blank($this->request->email)) {
            return;
        }

        $locale = $this->request->locale ?: config('app.locale', 'en');
        $productTitle = $this->request->product?->translate($locale)?->title
            ?? $this->request->product?->translate('en')?->title
            ?? 'Product';
        $downloadUrl = route('products.spec-download', ['token' => $this->request->download_token]);

        if (config('mail.default') === 'log') {
            Log::info('Product spec download approved', [
                'reference' => $this->request->reference_number,
                'email' => $this->request->email,
                'download_url' => $downloadUrl,
            ]);

            return;
        }

        Mail::to($this->request->email, $this->request->name)
            ->send(new ProductSpecDownloadApprovedMail(
                $this->request,
                $productTitle,
                $downloadUrl,
                $locale,
            ));
    }
}
