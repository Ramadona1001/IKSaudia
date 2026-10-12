<?php

namespace App\Jobs;

use App\Mail\ProductSpecDownloadRequestAdminMail;
use App\Mail\ProductSpecDownloadRequestCustomerMail;
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

        $locale = $this->request->locale ?: config('app.locale', 'en');
        $productTitle = $this->request->product?->translate($locale)?->title
            ?? $this->request->product?->translate('en')?->title
            ?? 'Product';

        $adminRecipients = $this->adminRecipients();

        if (config('mail.default') === 'log') {
            Log::info('Product spec download request received', [
                'reference' => $this->request->reference_number,
                'product' => $productTitle,
                'email' => $this->request->email,
                'admin_recipients' => $adminRecipients,
            ]);

            return;
        }

        $adminUrl = url('/'.config('cms.admin_path', 'ik-admin').'/product-spec-download-requests/'.$this->request->id.'/edit');

        if ($adminRecipients !== []) {
            try {
                Mail::to($adminRecipients[0])
                    ->cc(array_slice($adminRecipients, 1))
                    ->send(new ProductSpecDownloadRequestAdminMail(
                        $this->request,
                        $productTitle,
                        $adminUrl,
                    ));
            } catch (\Throwable $exception) {
                Log::error('product_spec.admin_mail_failed', [
                    'reference' => $this->request->reference_number,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        if (filled($this->request->email)) {
            try {
                Mail::to($this->request->email, $this->request->name)
                    ->send(new ProductSpecDownloadRequestCustomerMail(
                        $this->request,
                        $productTitle,
                        $locale,
                    ));
            } catch (\Throwable $exception) {
                Log::error('product_spec.customer_mail_failed', [
                    'reference' => $this->request->reference_number,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }

    /**
     * @return list<string>
     */
    protected function adminRecipients(): array
    {
        $emails = collect(setting('contact.form_recipients', []))
            ->pluck('email')
            ->merge([
                config('mail.contact_notify'),
                config('mail.from.address'),
            ])
            ->filter(fn ($email) => filled($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->map(fn ($email) => strtolower((string) $email))
            ->unique()
            ->values()
            ->all();

        return $emails;
    }
}
