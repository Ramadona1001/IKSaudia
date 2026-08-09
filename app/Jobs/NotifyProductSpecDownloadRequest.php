<?php

namespace App\Jobs;

use App\Models\ProductSpecDownloadRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyProductSpecDownloadRequest implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public ProductSpecDownloadRequest $request,
    ) {}

    public function handle(): void
    {
        $this->request->loadMissing(['product.translations']);

        $recipients = collect(setting('contact.form_recipients', []))
            ->pluck('email')
            ->filter()
            ->all();

        $to = $recipients[0] ?? config('mail.from.address');
        $productTitle = $this->request->product?->translate($this->request->locale)?->title ?? 'Product';

        if (! $to || config('mail.default') === 'log') {
            Log::info('Product spec download request received', [
                'reference' => $this->request->reference_number,
                'product' => $productTitle,
                'email' => $this->request->email,
            ]);

            return;
        }

        $adminUrl = url('/'.config('cms.admin_path', 'ik-admin').'/product-spec-download-requests/'.$this->request->id.'/edit');

        $lines = [
            'New product specification download request',
            '',
            'Reference: '.$this->request->reference_number,
            'Product: '.$productTitle,
            'Name: '.$this->request->name,
            'Email: '.$this->request->email,
            'Phone: '.$this->request->phone,
            'Company: '.($this->request->company ?: '—'),
            '',
            'Review in admin: '.$adminUrl,
        ];

        Mail::raw(
            implode("\n", $lines),
            fn ($message) => $message->to($to)->subject('[IK Saudi] PDF request: '.$this->request->reference_number),
        );
    }
}
