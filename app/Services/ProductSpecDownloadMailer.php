<?php

namespace App\Services;

use App\Mail\ProductSpecDownloadApprovedMail;
use App\Mail\ProductSpecDownloadRequestAdminMail;
use App\Mail\ProductSpecDownloadRequestCustomerMail;
use App\Models\ProductSpecDownloadRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProductSpecDownloadMailer
{
    public function sendRequest(ProductSpecDownloadRequest $request): void
    {
        $request->loadMissing(['product.translations']);

        $locale = $request->locale ?: config('app.locale', 'en');
        $productTitle = $request->product?->translate($locale)?->title
            ?? $request->product?->translate('en')?->title
            ?? 'Product';
        $adminRecipients = $this->adminRecipients();

        if (config('mail.default') === 'log') {
            Log::info('Product spec download request received', [
                'reference' => $request->reference_number,
                'product' => $productTitle,
                'email' => $request->email,
                'admin_recipients' => $adminRecipients,
            ]);

            return;
        }

        $adminUrl = url('/'.config('cms.admin_path', 'ik-admin').'/product-spec-download-requests/'.$request->id.'/edit');

        if ($adminRecipients !== []) {
            try {
                Mail::to($adminRecipients[0])
                    ->cc(array_slice($adminRecipients, 1))
                    ->send(new ProductSpecDownloadRequestAdminMail($request, $productTitle, $adminUrl));
            } catch (\Throwable $exception) {
                Log::error('product_spec.admin_mail_failed', [
                    'reference' => $request->reference_number,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        if (filled($request->email)) {
            try {
                Mail::to($request->email, $request->name)
                    ->send(new ProductSpecDownloadRequestCustomerMail($request, $productTitle, $locale));
            } catch (\Throwable $exception) {
                Log::error('product_spec.customer_mail_failed', [
                    'reference' => $request->reference_number,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }

    public function sendApproved(ProductSpecDownloadRequest $request): void
    {
        $request->loadMissing(['product.translations']);

        if (! $request->tokenIsValid() || blank($request->email)) {
            return;
        }

        $locale = $request->locale ?: config('app.locale', 'en');
        $productTitle = $request->product?->translate($locale)?->title
            ?? $request->product?->translate('en')?->title
            ?? 'Product';
        $downloadUrl = route('products.spec-download', ['token' => $request->download_token]);

        if (config('mail.default') === 'log') {
            Log::info('Product spec download approved', [
                'reference' => $request->reference_number,
                'email' => $request->email,
                'download_url' => $downloadUrl,
            ]);

            return;
        }

        Mail::to($request->email, $request->name)
            ->send(new ProductSpecDownloadApprovedMail($request, $productTitle, $downloadUrl, $locale));
    }

    /**
     * @return list<string>
     */
    protected function adminRecipients(): array
    {
        return collect(setting('contact.form_recipients', []))
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
    }
}
